# rewind

Rewind is a modular deobfuscation tool for PHP malware. It takes nasty code
that punks and miscreants leave on your server and turns it into slightly
more readable nasty code.

It is very much my playground at the moment and not suitable for production
use whatsoever, sorry about that. Pull requests welcomed, however!

## Usage

```
usage: rewind [<options>] [filename]

A modular tool for deobfuscating malware.

Filename: The file to operate on.

OPTIONS
  --help, -?   Display this help.
  --tree, -t   Output the generated AST instead of the code.
```

## Licence

GPL.