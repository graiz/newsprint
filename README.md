# newsprint

[The blog post and video](https://gregraiz.com/posts/i-made-an-eink-newspaper) that accompany this code give a general overview of the project. 

Newsprint is a simple web application that will fetch the front page of a newspaper and display it on an eink display. The specific resolutions and sizes have been setup to work with a 32" eInk place & play display from Visionect but can be modified for other screen resolutions.  

There are two portions to getting this up and running. The first is the application server that displays the newspaper. This code is setup to be run on a simple and low cost PHP webhost with very few dependancies.  Simply copy the PHP files into a directory on your server and you should be good to go. You will need an "archive" folder on the server and the ability to call the command line "convert" utility to resize/convert images. Most hosts should have this installed as it's fairly standard. 

The current newspapers that I've setup include the Boston Globe, New York Times, Wall St Journal, LA Times, Toronto Star and SF Chronical. Additional sources are easy to add by looking up the newspaper prefix on freedomforum.org. This is typically two letter State and newspaper abbreviation (NY_NYT or MA_BG). Each newspaper is then adjusted to display as much of the paper as possible. Many newspapers have a boarder or printing margin and I've tried to subtract this out in the samples.

The second portion of the software needed to get this running is the Visionect server. This has to run on the same network as the eInk display.  The display itself is not standalone, it's a thin client and requires the Visionect software to act as an HTML rendering engine of sorts.  The Visionect server software can be run on any docker server and general installation instructions are on the Visionect site. I was able to get it to run on my Synology server with a slightly modified file. The details of this are in the docker folder of this repo. 

[__Affiliate link to Visionect eInk Display that was used__](https://www.visionect.com/ref/graiz/)
