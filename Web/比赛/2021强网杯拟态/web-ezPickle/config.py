notadmin={"admin":"no"}

def backdoor(cmd):
    if notadmin["admin"]=="yes":
        s=''.join(cmd)
        eval(s)