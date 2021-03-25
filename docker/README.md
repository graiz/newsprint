The Visionect software can be downloaded and installed from the Docker container information provided on the visionect website:
> https://docs.visionect.com/VisionectSoftwareSuite/Installation.html

I was able to install the server on a Synology diskstation using the instructions provided however I needed to make some changes to get this to work. In particular I needed to ssh into my synology and modify the provided ports on the docker-compose.yml file to make this work. 

This seems to be because Synology already has a postgress DB instance and the default ports cause issues. I've included a modified docker-compose file for reference however the only change is the installed port numbers.  Consider downloading directly from Visionect and modifying the ports yourself if needed. 
