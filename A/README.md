# CS361-MicroserviceA-Grocery-List
This microservice provides a way for a user to add or remove items from a grocery list. It also provides a way for a user to view their current grocery list. The communication pipeline is a text file.

# Communication Contract
The communication contract includes instructions on how to request and receive data from the microservice. It also includes a UML diagram.

## Requesting Data
To request data from the microservice the user will need to provide a command ('Add', 'Remove', or 'View') on the first line of the GroceryList text file. If adding or removing an item the user should provide the ingredient on the second line of the text file.

For adding or removing an item:

```
import time

with open('GroceryList.txt', 'r+') as input_file:
    input_file.truncate(0)
    input_file.write('Add')
    input_file.write('\n')
    input_file.write('Bananas')

time.sleep(5)
```
For viewing grocery list:

```
import time

with open('GroceryList.txt', 'r+') as input_file:
    input_file.truncate(0)
    input_file.write('View')

time.sleep(5)
```

## Receiving Data
To receive data wait for the microservice to edit the GroceryList text file. For adding or removing an item read the first line of the text file for either 'Success' or 'Fail' to know whether the command was executed. For viewing the grocery list, read each line of the text file and print.

For adding or removing an item:

```
import time

with open('GroceryList.txt', 'r+') as input_file:
    output = input_file.readline()

if output == 'Success':
    print("Ingredient Removed!")

elif output == 'Fail':
    print('Ingredient Not Found')

time.sleep(5)
```

For viewing grocery list:

```
import time

with open('GroceryList.txt', 'r+') as input_file:
    print(input_file.read())
    input_file.truncate(0)

time.sleep(5)
```

## UML Diagram
![Grocery List UML Diagram](https://github.com/user-attachments/assets/0083c1a1-d874-4742-979c-01629aefd74c)

# Mitigation Contract
Teammate: Luke Miller

Microservice Status: Complete

Outstanding Issues: None

Microservice Access: Microservice will be provided via GitHub and should be run locally. As it is private please provide your username: https://github.com/msmith65/CS361-MicroserviceA-Grocery-List

Microservice Issues: If any issues are encountered please message me via Discord and I will respond within 24 hours. I am availalbe most weekends and weekday evenings. Please provide at least a 24 hour notice before any deadlines per our team's ground rules (This would be by the end of the day on May 31 as Assignment 10 is due on June 2).

Additional Information: N/A
