# course-lookup
Written by : lilyjma 

This is a command line tool that queries and provides statistics about departments and courses at Columbia. The course data is stored in a CSV called course-lookup-courses.csv.

This tool: 
1. imports data from the CSV into a database 
ex: php courses.php import course-lookup-courses.csv
2. searches for courses using a user entered call number 
ex: php courses.php lookup "COMS W3157"
3. calculates statistics about courses in the database. 
ex: php courses.php stats

The call number format for course search is {Subject Area Code} {Bulletin Prefix Code}{Course Number}. The stats functionality returns the top 5 departments that offer the most courses. It also returns the top 10 most frequently used words in courses names, excluding "the", "and", "in", "of", "to".

The program is implemented using PHP 5.3 and SQLite. 
