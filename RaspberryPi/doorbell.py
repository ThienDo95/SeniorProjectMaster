from gpiozero import LED, Button
from picamera import PiCamera
from PIL import Image
import zbarlight
import requests
import os

# Where the photos will be sent.
url = 'http://ec2-52-207-254-139.compute-1.amazonaws.com/face_recog.php'

btn = Button(23)    # button object

red = LED(26)       # red LED
amber = LED(19)     # amber LED
white = LED(13)     # white LED

state = 'check_token' # Starting State

# Controls LEDs according to state
def setLEDs(set):
    if(set is 'setup'): # No token found. Requires token setup
        red.on()
        amber.on()
        white.on()
    elif(set is 'setupB'):  # Sigal QR Scanner processing
        red.blink(on_time=0.1, off_time=0.1)    
        amber.blink(on_time=0.1, off_time=0.1)  
        white.blink(on_time=0.1, off_time=0.1) 
    elif(set is 'red'): # Ready for use
        red.on()
        amber.off()
        white.off()      
    elif(set is 'amber'): # Sending photo for comparison
        red.off()
        amber.blink(on_time=0.5, off_time=0.5)
    elif(set is 'white'): # Photo matches
        amber.off()
        white.on()
    elif(state is 'fail'): # Photo does not match
        amber.off()
        red.blink(on_time=0.2, off_time=0.2)
    elif(set is 'sww'): # Something Went Wrong (usually network connection)
        red.blink(on_time=0.2, off_time=0.2)
        amber.blink(on_time=0.2, off_time=0.2)
        white.blink(on_time=0.2, off_time=0.2)
    
token = ''

print('Program Started') # for testing

# will always run. Press [Ctrl] + C to interrupt app
while True:
    if(state is 'check_token'):
        # Check if token file already exist
        if(os.path.exists('token.txt')):
            with open('token.txt', "r") as t:
                token = t.read()
            # Check if file has anything in it
            if(token):
                state = 'red'   #if yes its ready for use
            else:
                state = 'setup' #if not send it to qr reader
        else:
            state = 'setup'
    elif(state is 'setup'):
        setLEDs(state)
        
        btn.wait_for_press()
        btn.wait_for_release()
        
        setLEDs('setupB')
        
        file_path = '/var/www/html/qr.jpg'
        
        # Camera takes photo
        with PiCamera() as cam:
            cam.rotation = 90
            cam.capture(file_path, format='jpeg')
            print('pic taken1')
        
        # Open photo for scanning
        with open(file_path, 'rb') as image_file:
            image = Image.open(image_file)
            image.load()
        
        # Scan photo for QR Code. If nothing found this will return None
        codes = zbarlight.scan_codes('qrcode', image)
        
        if(codes): # Check if any was found
            token = str(codes[0])
            token = token[2:len(token)-1]
            print(token)
            
            # Save token to file incase of power cycle
            with open('token.txt', 'w') as t:
                t.write(token)
            state = 'red' # Go to ready state
    elif(state is 'red'): # Wait for button press
        setLEDs(state)
        btn.wait_for_press()
        btn.wait_for_release()
        state = 'amber'
    elif(state is 'amber'): # Take and send photo, process results
        setLEDs(state)
        # take picture
        with PiCamera() as cam:
            cam.rotation = 90
            cam.capture('/var/www/html/face.jpg', format='jpeg')
        print('pic taken1')
        
        file = {'source': open('/var/www/html/face.jpg', 'rb')}

        # send images to specified url
        r = requests.post(url, files=file, data={'token': token})

        # read results
        results = r.text

        print("RESULTS: ", results) # For Testing

        if("False" in results): state = 'fail'   # No Match
        elif("True" in results): state = 'white' # Match. Unlock door
        else: state = 'sww' # Something Went Wrong

    elif(state is 'white'): # Match found

        setLEDs(state)
        # this button press will simulate unlocking door
        btn.wait_for_press()
        btn.wait_for_release()

        state = 'red'   # Go back to ready state
    elif(state is 'fail'): # No match found
        setLEDs(state)
        # this button press will simulate unlocking door
        btn.wait_for_press()
        btn.wait_for_release()

        red.off()
        state = 'red'   # Go back to default state
    elif(state is 'sww'): # Something went wrong
        setLEDs(state)
        btn.wait_for_press()
        btn.wait_for_release()

        red.off()
        state = 'red'   # Go back to ready state
