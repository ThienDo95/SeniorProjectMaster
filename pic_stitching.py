#!/usr/bin/python

from __future__ import print_function
import sys
import os.path
from PIL import Image

# Function used to find the largest width of all the images given
def findLargest(nums):
    nums.sort()
    return nums[len(nums)-1]
    
# Function used to find number of pics in given directory	
def findNumPicsInDir():
	count = 0
	for filename in os.listdir(os.getcwd()):
		if (filename.endswith(".jpeg") or filename.endswith(".jpg") or filename.endswith(".png")):
			count += 1
	return count


print(sys.argv[0])
print(sys.argv[1])
print(sys.argv[2])

#sets current dir to users/userID/
currentDir = os.getcwd() + "/users/" + sys.argv[1] + "/"
os.chdir(currentDir)
print(os.getcwd())
counter = 0
targetNum = 1
picList = [None]*findNumPicsInDir()


#if their is no file name, means to recreate target image
if (sys.argv[2] == "None"):
	print("In None part of IF")
	#loop through all images in directory
	for filename in os.listdir(os.getcwd()):
		#make sure only images are selected
		if filename.endswith(".jpeg") or filename.endswith(".jpg") or filename.endswith(".png"):
			#Open image
			im = Image.open(filename)
			#Put in list
			picList[counter] = im
			#up the counter
			counter += 1		
else: #Happens if the script is supplied a file to add
	print("In else part of IF")
	targetNum = sys.argv[3]
	#check the filesize to make sure target isn't greater than 5MB
	targetImgName = str(targetNum) + ".jpg"
	os.chdir("target/")
	print(os.getcwd())
	if (os.path.getsize(targetImgName) + int(sys.argv[4]) > 5242880):
		#If it is greater than 5MB, then create the next numbered target image
		im = Image.new("RGB", (1,1), "white")
		targetNum += 1
		targetImgName = targetNum + ".jpg"
		im.save(targetNamePlusDir)
	#Open the target, add it to the list, and up the counter
	targetImgName = str(targetNum) + ".jpg"
	im = Image.open(targetImgName)
	picList[counter] = im
	counter += 1
	os.chdir("..")
	#Add supplied image to list
	im = Image.open(sys.argv[2])
	picList[counter] = im
	counter += 1

#find the max width and total length
widths = [None]*counter
length = 0

for x in range (0, counter):
    #Put widths into width array
    widths[x] = picList[x].size[0]
    #sum up lengths
    length += picList[x].size[1]

largestWidth = findLargest(widths)
#print("")
print("Larget width: %d" % largestWidth)
print("Total length: %d" % length)

finalImage = Image.new("RGBA", (largestWidth, length), "white")
currentLength = 0

#paste the first image in
finalImage.paste(picList[0],(0,currentLength))

#set the current length of used space to the length of the first image
currentLength += picList[0].size[1]

#cycle from image 2-end
for x in range (1, counter):
    #put the next image in right below the previous image
    finalImage.paste(picList[x], (0,currentLength))
    #add latest picture to current length of used space
    currentLength += picList[x].size[1]

targetImgName = str(targetNum) + ".jpg"
print(targetImgName)
os.chdir("target")
print(os.getcwd())
finalImage.save(targetImgName)
#print("")
#print("Image stitched together and saved")