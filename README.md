# Joomla-Update-System-For-Paid-Extensions

This is a continuations of https://github.com/mabdelaziz77/downloadid-custom-field

Mohamed has developed a script that when paired with your extension inserts a input field into it and writes this data into joomla update table, setting "extra_query" used for validating your user subscription.

Now my script comes into play - it will take the value sent by Joomla "dlid" in this case and run it against the database to find a valid subscription.
After all checks pass trough it will send a file to Joomla for it to update.
A log file is created and each acces to download.php is loged.


You will need to point your update server to this "download.php" file

`<downloads><downloadurl type="full" format="zip">https://www.website.com/downloads/download.php</downloadurl></downloads>`


to make this work you will have to manually change the updated file inside download.php with each update (for now)

I'm using a custom solution to manage my payments, subscriptions and downloads - a combination of 2 free components, phocaDownload and PayPerDownload - for a free components it has everything you need when setting up in tandem they work quite flawlessly.

the only tricky thing about this is making it work with your subscription system - you will need to find correct tables and edit them accordingly.


there is some space for improvement of this script such as: 
- using joomla db connection
- getting a ral IP from the user behind cdn
- restricting update domains
- plugin for automatic file update inside download.php
