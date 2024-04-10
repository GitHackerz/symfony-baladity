from flask import Flask, render_template, Response, redirect , request
import cv2
import numpy as np
from pyzbar.pyzbar import decode

from Crypto.Cipher import AES
from Crypto.Util.Padding import unpad
from base64 import b64decode



app = Flask(__name__)
cap = None


# Decrypt function using AES decryption
def decrypt_string(encrypted_message, key):
    # Convert key to bytes
    key = key.encode('utf-8')

    # Decode the base64 encoded string and convert to bytes
    encrypted_message = b64decode(encrypted_message)

    # Create AES cipher object
    cipher = AES.new(key, AES.MODE_ECB)

    # Decrypt the message
    decrypted_message = unpad(cipher.decrypt(encrypted_message), AES.block_size)

    # Convert the decrypted bytes to string
    return decrypted_message.decode('utf-8')



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
                key = "azertyazertyazer"
                print(myData)

                if myData in ["acceptee", "Acceptee", "ACCEPTEE","EbHUsq5Gk+aAHpSfrcshjw=="]:
                    myOutput = 'Valide'
                    myColor = (0,255,0)
                else:
                    myOutput = 'Non Valide'
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




