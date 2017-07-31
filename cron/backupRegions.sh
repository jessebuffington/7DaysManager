#!/bin/bash
#
# Last modified 7/31/2017 - Jesse Buffington

backupName="7D_Regions"

backupDir="/media/server/backup/${backupName}"
fileName="${backupDir}/${backupName}_`date +%Y%m%d_%H%M`.gz"
backupFile="${backupName}_`date +%Y%m%d_%H%M`.gz"
regionDir="/home/steam/.local"
keepTo="3"

logDir="../log/"
logFile="regionBackup_`date +%Y%m%d_%H`.log"

exec 1> >(logger -s -t $(basename $0)) 2>&1 >> ${logDir}/${logFile}


echo -e "\n\t**Begin ${backupName} Backup**\n"

echo -e  "Archiving files to: " ${fileName}

# Zip it up
tar -czf ${backupDir}/${backupFile} ${regionDir}

echo -e  "\n\t**ANY FILES LISTED BELOW ARE OLDER THAN ${keepTo}+ DAYS AND WILL BE REMOVED!**\n\n"

# List files and remove last 10 days and remove them
find ${backupDir}/${backupFile}* -mtime +${keepTo}

echo -e  "Sleeping for 5 sec..."
sleep 1
echo -e  "Sleeping for 4 sec..."
sleep 1
echo -e  "Sleeping for 3 sec..."
sleep 1
echo -e  "Sleeping for 2 sec..."
sleep 1
echo -e  "Sleeping for 1 sec..."
sleep 1

find ${backupDir}/${backupFile}* -mtime +${keepTo} -print0 -exec rm '{}' \;

echo -e  "\n\n**Backup Complete**\n\n"
