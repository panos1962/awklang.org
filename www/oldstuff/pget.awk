#!/usr/local/bin/gawk -f
#
# pget.awk - generic parameter extraction engine
# Author: David Thompson, dat1965@yahoo.com
# Used with Permission
#
# NAME
#   pget.awk - generic parameter extraction engine
#
# SYNOPSIS
#   pget.awk [SECTION=section] [PARAM=name]
#            [DEBLANK=1] [TRIM=1] [JOIN={1|c}]
#            [TEST={n|z}] [KEEPHASH=1] [PRINT={0|1|2}]
#            [DEFAULT=x] [DEBUG=1] [file...]
#
# DESCRIPTION
#   pget.awk is a parameter extraction engine for initialization files,
#   which are text files containing parameter definitions.  A parameter
#   definition is a name and value pair separated by an '=' character.
#
#   Example parameter definitions,
#
#       PATH=/usr/bin:/bin
#       MANPATH=/usr/share/man:/usr/local/man
#       PAGER=more
#
#   Given the parameter name using "PARAM=name", pget.awk outputs the
#   value of parameter "name".  If the parameter name is not found,
#   then pget.awk prints the DEFAULT value (if DEFAULT is defined),
#   otherwise no output is produced.
#
#   Note that your shell environment can be viewed as sequence of
#   parameter definitions.
#
#   pget.awk fully understands input files without section headers
#   (see below), ie, the output of the Unix env command is valid input
#   to pget.awk.  Multiple input files are treated as if they were all
#   concatenated together.
#
#   Spaces are (mostly) insignificant.  The examples above could have
#   been formatted like,
#
#       PATH     = /usr/bin:/bin
#       MANPATH  = /usr/share/man:/usr/local/man
#          PAGER = more
#
#   Specifically, whitespace before and after the '=' is ignored,
#   as well as leading and trailing whitespace before and after
#   the parameter definition (ie, before the name and after the
#   value).
#
#   To print the value of your environment PATH, try this,
#
#       env | pget.awk PARAM=PATH
#
#   pget.awk fully supports traditional Microsoft Windows 3.1 ini files.
#   The Microsoft Windows 3.1 ini file contained section headers, which
#   are organizational checkpoints that group parameter definitions into
#   areas addressable by the section header.
#
#   In pget.awk, a section header begins with the '[' character in column
#   one, continues with the section header name, and closes with the ']'
#   character as the last nonwhite character on the line.  All whitespace
#   inside the '[' and ']' are significant.  Specify the section header
#   name using "SECTION=section".  Note that if SECTION is undefined, the
#   implied section is used.
#
#   pget.awk defines the "implied" section to refer to that portion of
#   the initialization file before the start of the first section header.
#   From pget.awk's point of view, all files have an implied section, even
#   if it is empty.  Traditional Microsoft Windows 3.1 ini files did not
#   have implied sections.
#
#   Note that the input file may have no section headers, in which case all
#   parameter definitions are part of the implied section.  With pget.awk,
#   it is extremely convenient to reserve the implied section for general
#   parameters, using a section header only when the parameter needs to be
#   identified with a specific purpose.  For example,
#
#       PAGER=more
#
#       [daisy may]
#       PAGER = less
#
#       [billy bob]
#       PAGER = less -k
#
#   defines 3 different parameters in 3 sections (the implied section plus
#   2 others).  Each of these parameters may be accessed with pget.awk,
#
#       pget.awk PARAM=PAGER
#       pget.awk SECTION="daisy may" PARAM=PAGER
#       pget.awk SECTION="billy bob" PARAM=PAGER
#
#   pget.awk treats parameters of the same name in different sections as
#   different, but parameters of the same name in the same section are the
#   same.  For example, this input,
#
#       [boot]
#       file = vxd.dll
#       file = kbd.dll
#       file =
#       file = vio.dll
#
#   with this command,
#
#       pget.awk SECTION=boot PARAM=file
#
#   generates this output,
#
#       vxd.dll
#       kbd.dll
#       vio.dll
#
#   pget.awk also supports continuation lines.  The above input could have
#   been written as,
#
#       [boot]
#       file = vxd.dll \
#       kbd.dll \
#       vio.dll
#
#   or
#
#       [boot]
#       file = \
#       vxd.dll \
#       kbd.dll \
#       vio.dll
#
#   Blank lines continued are handled correctly.  For example,
#
#       [lincoln]
#       TEXT = Four score and seven years ago, \
#       \
#       our forefathers ...
#
#   will be concatenated (with the blank line) as you would expect.
#   To remove this blank line, use DEBLANK=1, see below.
#
#   Note that pget.awk makes no requirement that a section actually contain
#   parameter definitions.  That is, section contents may contain any kind
#   of text data.  For example, this input,
#
#       [boot]
#       vxd.dll
#       kbd.dll
#       vio.dll
#
#   with this command,
#
#       pget.awk SECTION=boot
#
#   generates this output,
#
#       vxd.dll
#       kbd.dll
#       vio.dll
#
#   Use JOIN=1 to combine multi-line output into a single line,
#
#       pget.awk SECTION=boot JOIN=1
#
#   generates this output,
#
#       vxd.dll kbd.dll vio.dll
#
#   The default join delimiter is a single space, but it can be any
#   string.  Use JOIN=", " to generate this output,
#
#       vxd.dll, kbd.dll, vio.dll
#
#   JOIN=":" is also useful,
#
#       vxd.dll:kbd.dll:vio.dll
#
#   pget.awk recognizes comments if the characters ';' and '#' appear in
#   column 1.  Trailing comments, where the ';' or '#' occur after column
#   1, are not recognized.
#
#   pget.awk always ignores comments that begin with ';' and (by default)
#   will ignore '#' comments.  However, you may want to keep '#' comments
#   in the output.  For example, the following section defines a shell
#   script, using the ubiquitous hash bang as it's first line,
#
#       [shell script]
#       #!/bin/sh
#       echo hello world
#
#   pget.awk would normally remove the hash bang line because it sees the
#   hash '#' in column 1 as a comment.  Obviously, this line is not a comment
#   line intended for pget.awk but for the shell script itself.
#
#   Therefore, pget.awk supports special processing for all lines starting
#   with the '#' comment character.  This is the purpose of the KEEPHASH
#   variable, which is initialized internally as KEEPHASH=0.  You can retain
#   the '#' comments by specifying KEEPHASH=1 on the command line, like this,
#
#       pget.awk KEEPHASH=1 SECTION="shell script"
#
# COMMAND LINE OPTIONS
#   In actuality, the SECTION and PARAM variables are regular expressions.
#   Either one or both may be undefined.  If SECTION is undefined, then the
#   implied section is used.  If PARAM is undefined, then pget.awk outputs
#   the entire contents of the section.  See this chart below,
#
#     Is variable defined?
#     SECTION  PARAM    Output from pget.awk is:
#       Yes     Yes     values matching PARAM in SECTION
#       Yes     No      contents matching SECTION
#       No      Yes     values matching PARAM in implied section
#       No      No      contents of implied section
#
#   This chart is a subset of a larger chart.  See PRINT below.
#
#   DEBLANK={0|1}
#   If PARAM is undefined, then pget.awk prints the entire section
#   contents, including all blank lines.  Specify DEBLANK=1 to suppress
#   these blank lines.
#
#   TRIM={0|1}
#   If PARAM is undefined, then pget.awk prints the entire section
#   contents, including all leading and trailing whitespace.  Specify
#   TRIM=1 to strip this leading and trailing whitespace.
#
#   JOIN={0|1|c}
#   Multi-line parameters (via the continuation character '\') results
#   in multi-line output.  Also, if PARAM is undefined, pget.awk prints
#   the entire section contents, which may also result in multi-line output.
#   Specify JOIN=1 to concatenate multiple output lines into one output
#   line.  By default, JOIN=1 will separate concatenated lines with a
#   space.  You can alter this join delimiter using JOIN=c, where c is
#   any character or string.  Ie, JOIN=: and JOIN=", " are common.
#
#   Specifying JOIN={1|c} automatically implies DEBLANK=1 and TRIM=1.
#
#   TEST={z|n}
#   If TEST=z or TEST=n is defined, pget.awk sets the correct exit status
#   per the test primitive, and no output is produced.  Essentially, TEST=z
#   returns 0 if the output is length zero, while TEST=n returns 0 if the
#   output is non-zero length, otherwise, the pget.awk returns 1.  The 'z'
#   and 'n' values emulate the -z and -n options of the Unix test command.
#
#   KEEPHASH={0|1}
#   If KEEPHASH=1 is specified, then '#' comment lines are not removed
#   from the output.  Comments begin with ';' or '#' in column 1 only.
#   Comments starting with ';' are always stripped.  However, comments
#   starting with '#' are always kept for continuation lines (so use
#   the ';' character to comment out a continuation line), otherwise
#   pget.awk consults the KEEPHASH setting.  By default, KEEPHASH is
#   defined as zero so pget.awk strips comment lines starting with '#'.
#   To keep all comment lines starting with '#', use KEEPHASH=1.  This
#   is useful for section contents destined for a script language that
#   itself uses the '#' character as a comment, eg, sh, perl, awk, etc.
#
#   PRINT={0|1|2}
#   The default value PRINT=0 produces default output, it does *not*
#   disable output.  Consult the table below,
#
#     Is variable defined?
#     SECTION PARAM PRINT Output from pget.awk is:
#       Yes    Yes    0   values matching PARAM in SECTION (default)
#       Yes    Yes    1   names matching PARAM in SECTION
#       Yes    Yes    2   definitions matching PARAM in SECTION
#       No     Yes    0   values matching PARAM in implied section (default)
#       No     Yes    1   names matching PARAM in implied section
#       No     Yes    2   definitions matching PARAM in implied section
#       Yes    No     0   contents matching SECTION (default)
#       Yes    No     1   headers matching SECTION
#       Yes    No     2   header+content matching SECTION
#       No     No     0   contents of implied section (default)
#       No     No     1   all parameter names of implied section
#       No     No     2   all parameter definitions of implied section
#
#   DEFAULT=x
#   If the values of SECTION and PARAM would normally produce no
#   output, pget.awk prints the DEFAULT value instead, if DEFAULT is
#   defined.  If the DEFAULT is printed, it is not subject to DEBLANK,
#   TRIM, or JOIN.  Furthermore, processing of TEST precedes DEFAULT,
#   so that TEST=z works even if DEFAULT is defined.
#
#   DEBUG={0|1}
#   pget.awk supports an internal debugging mode that can be enabled
#   using DEBUG=1.  This output is intended for maintainers and advanced
#   users.
#
# DESIGN NOTES
#   An early design decision allowed comments to start with a ';' or
#   a '#', but this was soon abandoned in favor of ';' only.  Then
#   it was discovered that '#' could benefit from special handling,
#   since you might not want these comments stripped in all cases.
#   So the '#' as a comment character was reinstated, but with some
#   user control over the output handling.
#
#   pget.awk follows these rules for comment processing,
#
#       A. If the line matches /^;/, it is a comment and it is always
#       stripped.  The parameter lookup code in the END block never
#       sees these lines.
#
#       B. If the line matches /^#/, it is a comment that receives
#       special handling,
#
#           1. If the line is a continuation line, it is always kept.
#           2. If the KEEPHASH=1 was specified, it is kept.
#           3. Otherwise, the line is stripped and the END block
#           never sees it.
#
#   This input was used for testing,
#
#       #!/bin/sh
#       echo hello implied
#       echo goodbye implied
#
#       [test1]
#       #!/bin/sh
#       echo hello test1
#       echo goodbye test1
#
#       [test2]
#       TEST = \
#       #!/bin/sh \
#       echo hello test2 \
#       [ -f /etc/passwd ] && echo found /etc/passwd \
#       echo goodbye test2
#
#       [test3]
#       TEST = \
#       \
#       \
#       \
#
#   pget.awk illustrates a common design technique known as hunt and
#   gather.  Hunt and gather is a private term invented by the author
#   to identify certain types of simple state machine algorithms.
#
#   awk is an exceptionally well-suited tool for this technique because
#   awk's default mode is to hunt for lines matching regular expressions.
#   Aficionados of perl will note that perl, too, is well-suited for this
#   problem solving technique.
#
#   Once lines of interest are identified by hunting, ie, matching one or
#   more regular expressions, gather mode is enabled and input lines are saved
#   in an array.  Even while gathering, hunting continues, so that pget.awk
#   might know when to disable gathering.
#
#   Gather mode is used to collect the section contents.  After all input
#   files have been processed and all lines have been gathered, the END
#   section of pget.awk is used to inspect these gathered lines and search
#   for parameters.  The END section builds a second array that contains
#   the lines to output, and, finally, this array is printed to stdout.
#
#   Note that the engine design of pget.awk specifically precludes the
#   need to specify nawk's -v option.  This is intentional; the author
#   would beat himself silly every time he forgot to specify -v, so this
#   awk command line option has been avoided.
#
#   Note that to support an implied section, gather mode is initially
#   enabled.  Traditional MS Windows 3.1 ini files did not have implied
#   sections.
#
# DESIGN RULES
#   1. Comments start with a ';' or '#' in column 1 and these lines may
#   appear anywhere in the input.  Trailing statement comments are not
#   supported.  That is, if the ';' or '#' appear in any column other
#   than column 1, it is not a comment.
#
#   2. Comments starting with ';' are always removed.
#
#   3. Comments starting with '#' are kept if the line is a continuation
#   line, otherwise it is removed, unless KEEPHASH=1 is defined.
#
#   4. Blank lines before and after a section are ignored.  But blank lines
#   intermixed in a section are printed if PARAM is undefined.  Use
#   DEBLANK=1 to suppress these blank lines.
#
#   5. Blank lines continued are preserved.
#
#   6. Section headers are introduced only by a '[' in column 1.
#
#   7. A valid section header must have ']' as the last nonwhite character
#   on the line.
#
#   8. Whitespace in SECTION and PARAM names is significant.
#
#   9. Whitespace before or after the equals '=' is removed.
#
#   10. Whitespace before or after the parameter definition is removed.
#
#   11. Whitespace before or after the continuation character '\' is
#   insignificant.  The '\' must be the last nonwhite character.
#
#   12. All whitespace in continuation lines is significant, except for
#   whitespace before and after the continuation character '\' (if present),
#   which is stripped.
#
#   13. Parameter definitions defined as empty result in no output.
#
#   14. Empty parameter definitions continued, ie, "NAME = \", are handled
#   properly.  No initial blank line is output by an empty definition
#   continued.
#
#   15. Embedded continuation markers are preserved, ie, "NAME = \\", works
#   as expected.
#
#   16. Same name parameters in the same section all get extracted.
#
#   17. Same name parameters in different sections are different.
#
# EXAMPLES
#   1. Assuming this input-file,
#

#       [general]
#       SCRIPT = \
#       #!/bin/sh \
#       echo hello world \
#       echo goodbye world
#
#   with this command,
#
#       pget.awk SECTION=general PARAM=SCRIPT <input-file>
#
#   results in these 3 lines of output,
#
#       #!/bin/sh
#       echo hello world
#       echo goodbye world
#
#   2. If the input-file was written like this,
#
#       [general]
#       #!/bin/sh
#       echo hello world
#       echo goodbye world
#
#   This this command,
#
#       pget.awk SECTION=general KEEPHASH=1 <input-file>
#
#   would produce the same output.
#
# TIPS
#   1. The implied section allows some interesting organization choices.
#   For example, the implied section can be used to store official notes
#   describing the input.  This embedded documentation is viewable just
#   by specifying 'pget.awk filename'.  Therefore, the implied section
#   can be ideal for documenting the purpose of the file, such as the
#   different section header names.
#
#   2. Since pget.awk prints section contents if PARAM is undefined,
#   pget.awk can be used to build a shar like archive, where section
#   names are well known names.  The contents of each archive section
#   are retrievable by specifying SECTION=<archive section>.
#
#   3. The ';' is the absolute comment character, only ';' can remove
#   lines from the output for all cases.  Using '#' as the comment
#   character still makes the lines retrievable using KEEPHASH=1.
#
#   4. Using KEEPHASH=1 may allow you to design internal parameter
#   definitions, ie, '#pragma = value' is retrievable if KEEPHASH=1
#   and PARAM="#pragma" is specified.
#
#   5. The case of SECTIONs and PARAMs *is* significant, but pget.awk
#   does not enforce any particular case policy.  If you need case
#   insensitivity, define it yourself, like this,
#
#       Case Sensitive
#           pget.awk SECTION=general ...
#
#       Case Insensitive
#           pget.awk SECTION="[Gg][Ee][Nn][Ee][Rr][Aa][Ll]" ...
#
# CAVEATS
#   1. Specifying case insensitive SECTION and PARAM variables is a
#   pain.  There should be a better way.
#
#   2. Specifying SECTION="[general]" is probably not what you want.
#   Don't delimit your section name with outer brackets, pget.awk
#   does that for you automatically.  Use SECTION=general instead.
#
#   3. If you specify JOIN=1, you always get deblanking and trimming.
#   That is, trying to force suppress these by specifying JOIN=1
#   with DEBLANK=0 TRIM=0 doesn't work.  This is by design.
#
#   4. DEBLANK=1 never removes blank continuation lines, these lines
#   are assumed to be significant.  (Otherwise, you wouldn't have
#   put a continuation marker on that line, right?)
#
#   5. KEEPHASH=1 never removes '#' comment lines continued, these
#   lines are assumed to be significant.  (Otherwise, you wouldn't
#   have put a continuation marker on that line, right?)  Use ';'
#   to comment out a continuation line.
#
#   6. TRIM=1 always works.  In general, you shouldn't consider any
#   whitespace as significant, as pget.awk strips quite a bit of
#   whitespace even when TRIM is undefined.
#
#   7. Specifying PARAM=";NAME" never works.  Using PARAM="#NAME"
#   only works if KEEPHASH=1 was specified.  This is correct behavior,
#   since comment lines are (usually) never seen by the END block.
#
#   8. A line containing the string "[]" is not a valid section
#   header; at least one character must appear between the brackets.
#
#   9. You can design some sections to be content sections with no
#   parameters, while other sections contain only parameter definitions.
#   Mixing content with parameter definitions in the same section works
#   correctly, but may not be very useful.  However, using KEEPHASH=1
#   and '#' to mark private variables in content sections can be
#   incredibly useful.
#

## -- print debugging output
function debug(s) {
    if (DEBUG) print "DEBUG: pget.awk: " s
}

## -- initialize internal global variables
## -- but not variables from command line
## -- which would require use of -v option
BEGIN {
    DEBUG = 0
    gather = 1
    implied = 1
}

## -- always skip over these comments
/^;/ {next}

## -- may need to keep these comments
/^#/ {
    if (!KEEPHASH) {
        if (!continueline) next
        }
}

## -- skip blank lines, but count them
/^[ \r\t]*$/ {
    if (DEBLANK) next
    ++blankline
    next
}

## -- enable gathering if matching section
## -- disable gathering on all other sections
## -- flush all lines previously gathered
/^\[.+\][ \r\t]*$/ {
    blankline = 0
    continueline = 0
    if (SECTION && implied) {
        debug("FLUSHING IMPLIED SECTION")
        nline = 0
        implied = 0
        }
    if (SECTION && match($0, "^\\\[" SECTION "\\\][ \r\t]*$")) {
        debug("FOUND SECTION: " $0)
        if (PRINT && !PARAM) {
            v = $0
            sub(/^\[/, "", v)
            sub(/\][ \r\t]*$/, "", v)
            if (PRINT == 2) {
                v = "[" v "]"
                if (nline) {
                    debug("GATHERING: NR=" NR ",nline=" nline+0 ": <blank>" )
                    line[nline++] = ""
                    }
                }
            debug("GATHERING: NR=" NR ",nline=" nline+0 ": " v)
            line[nline++] = v
            if (PRINT == 2) gather = 1
            else gather = 0
            }
        else {
            debug("GATHERING: enabled")
            gather = 1
            }
        }
    else {
        debug("SKIPPING SECTION: " $0)
        if (gather) debug("GATHERING: disabled")
        gather = 0
        }
    next
}

## -- if gathering a section, store line in array;
## -- otherwise the line is discarded; but first
## -- recreate all intervening blank lines; note
## -- since this code never actually sees the blank
## -- line itself, all trailing blank lines in the
## -- section are conveniently discarded
{
    if (!gather) next
    if (!(JOIN || DEBLANK)) {
        for (x = 1; x <= blankline; ++x) {
            debug("GATHERING: NR=" NR ",nline=" nline+0 ": <blankline>")
            line[nline++] = ""
            }
        }
    blankline = 0
    debug("GATHERING: NR=" NR ",nline=" nline+0 ": " $0)
    line[nline++] = $0
    if ($0 ~ /\\[ \r\t]*$/)
        continueline = 1
    else
        continueline = 0
}

## -- main engine to extract parameter from section
## -- here we inspect and edit the gathered lines
## -- finally, the gathered lines are printed, if any
END {
    ## -- if have SECTION but implied still true then
    ## -- specified SECTION was never found
    if (SECTION && implied) {
        debug("Section \"[" SECTION "]\" not found, flushing all gathered lines")
        nline = 0
        }

    ## -- if have PARAM then find PARAM in gathered lines
    ## -- if PRINT == 0 then get value
    ## -- if PRINT == 1 then get name
    ## -- if PRINT == 2 then get definition
    if (PARAM) {
        debug("Trying match \"" PARAM "\" in " nline+0 " gathered lines")
        MATCH = "^[ \r\t]*" PARAM "[ \r\t]*="
        continueline = 0
        for (x = 0; x < nline; ++x) {
            v = line[x]
            if (continueline) {
                sub(/[ \r\t]+$/, "", v)
                if (v ~ /\\$/) {
                    if (!PRINT || JOIN)
                        v = substr(v, 1, length(v)-1)
                        sub(/[ \r\t]+$/, "", v)
                    }
                else continueline = 0
                if (PRINT == 1) v = ""
                if (v) value[nvalue++] = v
                }
            else if (v ~ MATCH) {
                if (!PRINT) {
                    sub(MATCH, "", v)
                    sub(/^[ \r\t]+/, "", v)
                    }
                sub(/[ \r\t]+$/, "", v)
                if (v ~ /\\$/) {
                    continueline = 1
                    if (!PRINT || JOIN) {
                        v = substr(v, 1, length(v)-1)
                        sub(/[ \r\t]+$/, "", v)
                        }
                    }
                if (PRINT == 1) {
                    sub(/^[ \r\t]+/, "", v)
                    sub(/[ \r\t]*=.*$/, "", v)
                    }
                if (v) value[nvalue++] = v
                }
            }
        ## -- copy parameter definition to output array
        nline = nvalue
        for (x = 0; x < nvalue; ++x)
            line[x] = value[x]
        }

    ## -- if SECTION empty and PARAM empty then
    ## -- check if PRINT requires more filtering
    ## -- otherwise entire section is printed as is
    else if (!SECTION && PRINT)
        {
        continueline = 0
        for (x = 0; x < nline; ++x) {
            v = line[x]
            sub(/[ \r\t]+$/, "", v)
            if (continueline) {
                if (v !~ /\\$/) continueline = 0
                if (PRINT == 1) v = ""
                }
            else {
                if (v ~ /\\$/) continueline = 1
                if (PRINT == 1) {
                    sub(/^[ \r\t]+/, "", v)
                    sub(/[ \r\t]*=.*$/, "", v)
                    }
                }
            if (v) value[nvalue++] = v
            }
        nline = nvalue
        for (x = 0; x < nvalue; ++x)
            line[x] = value[x]
        }

    ## -- mimic test -z primitive
    if (TEST == "z") {
        if (nline) exit 1
        else exit 0
        }

    ## -- mimic test -n primitive
    if (TEST == "n") {
        if (nline) exit 0
        else exit 1
        }

    ## -- here we trim all leading & trailing whitespace;
    ## -- except for leading whitespace in continuation lines,
    ## -- all whitespace trimming has already been done for
    ## -- parameter definitions but whitespace trimming of
    ## -- the section contents has not occurred at all
    if (TRIM || JOIN) {
        for (x = 0; x < nline; ++x) {
            sub(/^[ \r\t]+/, "", line[x])
            sub(/[ \r\t]+$/, "", line[x])
            }
        }

    ## -- here we concatenate all output lines, if necessary
    if (JOIN) {
        if (JOIN ~ /[0-9]+/) JOIN = " "
        for (x = 0; x < nline; ++x) {
            if (line[x] || JOIN != " ") {
                if (j)
                    j = j JOIN line[x]
                else
                    j = line[x]
                }
            }
        ## -- reset array for single output line
        if (j) {
            nline = 1
            line[0] = j
            }
        else nline = 0
        }

    ## -- if no output, use DEFAULT, if provided
    if (nline == 0 && DEFAULT) {
        nline = 1
        line[0] = DEFAULT
        }

    ## -- all output is done from here
    for (x = 0; x < nline; ++x)
        print line[x]

    ## -- exit status is based upon output produced
    if (nline) exit 0
    else exit 1
}
