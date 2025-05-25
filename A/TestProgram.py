import time


def write_file(command, ingredient):
    """
    Writes the user's command and ingredient to a text file.
    """
    try:
        with open('GroceryList.txt', 'r+') as input_file:
            input_file.truncate(0)
            input_file.write(command)
            input_file.write('\n')
            input_file.write(ingredient)

    except FileNotFoundError:
        print("File not found")

    time.sleep(10)

    return None


def add_output():
    """
    Writes the outcome of the Add command.
    """
    try:
        with open('GroceryList.txt', 'r+') as input_file:
            output = input_file.readline()

        if output == 'Success':
            print("Ingredient Added!")

    except FileNotFoundError:
        print("File not found")

    time.sleep(5)

    return None


def remove_output():
    """
    Writes the outcome of the Remove command.
    """
    try:
        with open('GroceryList.txt', 'r+') as input_file:
            output = input_file.readline()

        if output == 'Success':
            print("Ingredient Removed!")

        elif output == 'Fail':
            print('Ingredient Not Found')

    except FileNotFoundError:
        print("File not found")

    time.sleep(5)

    return None


def view_output():
    """
    Writes the outcome of the View command.
    """
    print('\nThis is your grocery list:')

    try:
        with open('GroceryList.txt', 'r+') as input_file:
            print(input_file.read())

    except FileNotFoundError:
        print("File not found")

    time.sleep(5)

    return None


if __name__ == "__main__":

    time.sleep(5)

    # Adding first ingredient
    write_file('Add', 'Bananas')
    add_output()

    # View grocery list
    write_file('View', '')
    view_output()

    # Adding second ingredient
    write_file('Add', 'Carrots')
    add_output()

    # View grocery list
    write_file('View', '')
    view_output()

    # Adding third ingredient
    write_file('Add', 'Chocolate')
    add_output()

    # View grocery list
    write_file('View', '')
    view_output()

    # Removing chocolate ingredient
    write_file('Remove', 'Carrots')
    remove_output()

    # View grocery list
    write_file('View', '')
    view_output()
