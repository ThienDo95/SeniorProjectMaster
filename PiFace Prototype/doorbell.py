from gpiozero import LED, Button
from picamera import PiCamera
from time import sleep
import requests
import os

# Where the photos will be sent.
url = 'http://ec2-52-207-254-139.compute-1.amazonaws.com/test/face_recognition.php'

btn = Button(17)    # button object

red = LED(26)       # red LED 
amber = LED(19)     # amber LED
white = LED(13)     # white LED

state = 'red'       # Default State

print('Program Started') # for testing

# will always run. Press [Ctrl] + C to interrupt app
while True: 
    if(state is 'red'):
        red.on()
        amber.off()
        white.off()
        btn.wait_for_press()
        btn.wait_for_release()
        state = 'amber' 
    elif(state is 'amber'):
        red.off()
        amber.blink(on_time=0.5, off_time=0.5)
        
        # take picture
        with PiCamera() as cam:
            cam.rotation = 90
            #cam.resolution = '640x480'
            cam.capture('/var/www/html/face.jpg', format='jpeg')
        print('pic taken1')
        
        
        file = {'target': open('/var/www/html/face.jpg', 'rb')}
        
        # send images to specified url
        r = requests.post(url, files=file)

        # read results
        results = r.text
        
        
        print("RESULTS: ", results)

        #state = 'total failure'
        
        
        if("False" in results): state = 'fail'   # No Match
        elif("True" in results): state = 'white' # Match. Unlock door
        #For Debugging
        #elif(results is ''): state = 'blank'
        #else: state = 'total failure'
          
    elif(state is 'white'):
        amber.off()
        white.on()

        # this button press will simulate unlocking door
        btn.wait_for_press()
        btn.wait_for_release()
        
        state = 'red'   # Go back to default state.
    elif(state is 'fail'):
        amber.off()
        red.blink(on_time=0.5, off_time=0.5)
        
        # this button press will simulate unlocking door
        btn.wait_for_press()
        btn.wait_for_release()
        
        red.off()
        state = 'red'   # Go back to default state.
    '''
    #For Debugging
    elif(state is 'total failure'):
        print('FAILED')
        sleep(30)
    elif(state is 'blank'):
        print('BLANK')
        sleep(30)   
    '''   
        
