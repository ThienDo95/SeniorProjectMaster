from __future__ import print_function
import sys
import os.path
from PIL import Image

# Function used to find the largest width of all the images given
def findLargest(nums):
    nums.sort()
    return nums[len(nums)-1]

# Sets the current working directory to whatever (usually /users/#/)
currentDir = raw_input("Enter full path of working directory: ")
print("")
os.chdir(currentDir)

# Usually going to be 1 (i.e. run this script upon each picture being added)
numberOfPics = input("How many pictures do you want to stitch together: ")
print("")
picList = [None]*numberOfPics

#gather all the images into a list
for x in range(0,numberOfPics):
    fileName = raw_input("Enter full name  of picture to stitch: ")
    print("\t%s" % fileName)
    im = Image.open(fileName)
    print("\t", im.format, im.size, im.mode)
    picList[x] = im
    print("--------------------------------")

#find the max width and total length
widths = [None]*numberOfPics
length = 0
for x in range (0, numberOfPics):
    #Put widths into width array
    widths[x] = picList[x].size[0]
    #sum up lengths
    length += picList[x].size[1]

largestWidth = findLargest(widths)
print("")
print("Larget width: %d" % largestWidth)
print("Total length: %d" % length)

finalImage = None
currentLength = 0

#if target.jpeg exists, then you have to do some special things
if (os.path.isfile("target.jpeg")):
    # Open the old target image
    im = Image.open("target.jpeg")
    # Find whats larger: the largest width of new pictures or the target pic
    largestWidth = findLargest([largestWidth, im.size[0]])
    # add the length of the target to the total size
    length += im.size[1]
    currentLength = im.size[1]
    #create a new image to hold all the images provided
    finalImage = Image.new("RGBA", (largestWidth, length), "white")
    #put old target pic into new target pic
    finalImage.paste(im, (0,0))
else:
    #create a new image to hold all the images provided
    finalImage = Image.new("RGBA", (largestWidth, length), "white")



#paste the first image in
finalImage.paste(picList[0],(0,currentLength))

#set the current length of used space to the length of the first image
currentLength += picList[0].size[1]

#cycle from image 2-end
for x in range (1, numberOfPics):
    #put the next image in right below the previous image
    finalImage.paste(picList[x], (0,currentLength))
    #add latest picture to current length of used space
    currentLength += picList[x].size[1]
    
finalImage.save("target.jpeg")
print("")
print("Image stitched together and saved")
