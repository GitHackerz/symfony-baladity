from flask import Flask, render_template, Response, redirect , request
import cv2
import numpy as np
from pyzbar.pyzbar import decode

app = Flask(__name__)
cap = None

def generate_frames():
    global cap
    cap = cv2.VideoCapture(0)
    cap.set(3,640)
    cap.set(4,480)

    while True:
        success, img = cap.read()
        if not success:
            break
        else:
            for barcode in decode(img):
                myData = barcode.data.decode('utf-8')
                print(myData)

                if myData in ["valid", "Valid", "VALID"]:
                    myOutput = 'Valid'
                    myColor = (0,255,0)
                else:
                    myOutput = 'Invalid'
                    myColor = (0, 0, 255)

                pts = np.array([barcode.polygon], np.int32)
                pts = pts.reshape((-1, 1, 2))
                cv2.polylines(img, [pts], True, myColor, 5)
                pts2 = barcode.rect
                cv2.putText(img, myOutput, (pts2[0], pts2[1]), cv2.FONT_HERSHEY_SIMPLEX, 0.9, myColor, 2)

            ret, frame = cv2.imencode('.jpg', img)
            yield (b'--frame\r\n'
                   b'Content-Type: image/jpeg\r\n\r\n' + bytearray(frame) + b'\r\n')

@app.route('/')
def index():
    return render_template('QrScanner.html')

@app.route('/video_feed')
def video_feed():
    return Response(generate_frames(), mimetype='multipart/x-mixed-replace; boundary=frame')

@app.route('/stop_feed')
def stop_feed():
    global cap
    if cap:
        cap.release()
    return '1'

@app.route('/close_connection')
def close_connection():
    func = request.environ.get('werkzeug.server.shutdown')
    if func:
        func()
    return 'Connection closed.'

if __name__ == '__main__':
    app.run(debug=True)
