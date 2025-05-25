import time


def read_file():
    """
    Reads the GroceryList text file.
    """
    try:
        with open('GroceryList.txt', 'r+') as input_file:
            command_raw = input_file.readline()
            command_strip_raw = command_raw.strip("\n")
            ingredient_raw = input_file.readline()

            user_command = command_strip_raw.capitalize()
            ingredient = ingredient_raw.capitalize()

    except FileNotFoundError:
        print("File not found")

    return user_command, ingredient


def write_list(list):
    """
    Writes the grocery list to the text file.

    Referenced https://www.geeksforgeeks.org/reading-and-writing-lists-to-a-file-in-python/
    """
    try:
        with open('GroceryList.txt', 'r+') as input_file:
            for item in list:
                input_file.write('%s\n' % item)

    except FileNotFoundError:
        print("File not found")

    return None


def write_output(message):
    """
    Writes the outcome of the Add or Remove command.
    """
    try:
        with open('GroceryList.txt', 'r+') as input_file:
            input_file.truncate(0)
            input_file.write(message)

    except FileNotFoundError:
        print("File not found")

    return None


if __name__ == '__main__':

    time.sleep(2)

    grocery_list = []

    while True:

        # Read GroceryList.txt
        (user_command, ingredient) = read_file()

        # Add ingredient to grocery list
        if user_command == 'Add':
            grocery_list.append(ingredient)

            time.sleep(2)
            write_output('Success')

        # Remove ingredient from grocery list
        elif user_command == 'Remove':

            if ingredient in grocery_list:
                grocery_list.remove(ingredient)

                time.sleep(2)
                write_output('Success')

            else:
                time.sleep(2)
                write_output('Fail')

        # View grocery list
        elif user_command == 'View':
            write_list(grocery_list)

            time.sleep(2)
