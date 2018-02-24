from gpiozero import LED, Button
from picamera import PiCamera
from time import sleep
import requests

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
            cam.capture('face', format='jpeg')
        
        # store byte images for sending    
        file = {'target': target}
        
        # send images to specified url
        r = requests.post(url, data=file)
        
        # read results
        results = r.text
        
        # NOT TESTED YET Need to find how results are printed.
        if(results is 'False'):   # No match. Go to Fail
            state = 'fail'
        else:
            state = 'white' # Match. Unlock door
                
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
        
        
 
