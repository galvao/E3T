# E³T
<img src="media/logo.svg" width="200">

Experimental, Ever-Evolving Terminal

First of all, a couple of necessary warnings:

> **Warning**
This software is in **alpha**, meaning that it has quite a few known flaws, including design ones. On top of that, this is software intended for people who work in IT, so don't install it if you don't know what you're doing.

## Exit Error Codes

While text messages will be shown that are pretty self-explanatory, this is the error codes for quick reference:

|error code|reason|
|---|---|
|1|Wrong PHP's SAPI, e.g.: Trying to run E³T as a web page.|
|2|PHP's readline extension not installed or loaded.|

## Command Parsing Constraints

Every command is expected to follow the following specs:

1. No multiple flags with only a single dash:
    
    e.g.: 

`ls -lA`   <- Wrong
          
`ls -l -A` <- Right

2. Flags that have value must not have spaces between the flag, the equal sign and the value:
    
    e.g.: 
    
`find --name = 'foo\bar'` <- Wrong
          
`find --name='foo\bar'`   <- Right

3. All flag values must be quoted:

    e.g.: 
    
`find --name=foo\bar` <- Wrong
          
`find --name='foo\bar'`   <- Right