
Steps to get it working:
=========================

* call https://trello.com/1/appKey/generate in a browser to get the "key"
* call https://trello.com/1/connect?key=YOURKEYHERE&name=MyApp&response_type=token
  in a browser to get the "token"
* call https://api.trello.com/1/member/YOURTRELLOUSERNAME?key=YOURKEY&boards=all&token=YOURTOKEN
  in a browser to get the boardId yo use- look for: "boards":[{"id":"4f13ee1e","name":".."
  and use the id
* put key, token and boardId into /app/config/config.php
* run /bin/exporter
* look for the generated HTML in var