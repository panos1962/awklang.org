# Path: iad-read.news.verio.net!dfw-artgen.news.verio.net!dfw-peer.news.verio.net!news.verio.net!newsfeed.mathworks.com!dispose.news.demon.net!news.demon.co.uk!demon!cranley.demon.co.uk!alan
# From: Alan Linton <alan@cranley.demon.co.uk>
# Newsgroups: comp.lang.awk
# Subject: Re: mimicking stacks in gawk
# Date: Mon, 30 Jul 2001 23:30:04 +0100
# Message-ID: <Vm3TSAAs$dZ7UwN0@cranley.demon.co.uk>
# References: <Pine.SGI.4.05.10107292319190.1071939-100000@darwin.core>
# NNTP-Posting-Host: cranley.demon.co.uk
# X-NNTP-Posting-Host: cranley.demon.co.uk:158.152.106.13
# X-Trace: news.demon.co.uk 996532251 nnrp-12:28581 NO-IDENT cranley.demon.co.uk:158.152.106.13
# X-Complaints-To: abuse@demon.net
# MIME-Version: 1.0
# X-Newsreader: Turnpike Integrated Version 4.02 U <ALkhyf+f87uvcejdHbO28vxnxA>
# Lines: 103
# Xref: dfw-artgen.news.verio.net comp.lang.awk:10121
# 
# In article <Pine.SGI.4.05.10107292319190.1071939-100000@darwin.core>,
# Nat <nathaniel.echols@yale.edu> writes
# >I was visiting my parents a few weeks ago, and found myself without a
# >network connection for a shocking three days.  So I decided to try
# >implementing the "Towers of Hanoi" puzzle in as many languages and
# >compilers as possible (classic and ancient puzzle, easily reduced to a
# >simple algorithm).  It'd be an interesting way to benchmark some fairly
# >basic stuff in various forms; I study bioinformatics so massive data
# >structures are of great interest, and since I use a variety of languages
# >for my daily needs I'm curious about their relative processing power.  The
# >results with gcc are quite shocking (executable compiled with 3.0 is far,
# >far faster than 2.95 with optimization enabled, but slightly slower
# >without.  Hmm....).
# >
# >Anyway, C and Perl are done; Python or Java should take 30 minutes tops;
# >FORTRAN 77 shouldn't be too hard.  Might try C++ or F90 too.  Eventually,
# >other stuff (any ideas?).  Awk, however, presents a problem.
# >
# >I've been reading Robbins' book, and I'm disturbed by the
# >"multidimensional" arrays.  Awk seems to be the only language among the
# >above that doesn't have regular arrays in this respect.  Obviously, given
# >the nature of the problem, I'm using three stacks.  With C I can use a
# >linked list, with Perl the built in push() and pop() array functions do
# >all I need.  With F77 and Awk I have to fake it.  Because of the way I'm
# >doing this algorithm, I need to treat the three stacks as an array:
# >       @Pins = ([], [], []); # In Perl
# >       struct list Pins[3]; /* In C */
# >swapping the outer indices between loops.  This doesn't seem to work right
# >in Awk.  I want to do something like this:
# >        for(i = 0; i < 8; i++){
# >                Pins[0,i] = 8 - i;
# >        }
# >and then (using my own push() and pop() functions):
# >       top_ring = pop(Pins[0]);
# >       push(Pins[1], top_ring);
# >which currently seems to result in a hung script.
# >
# >Am I out of my mind if I expect anything like this to work?  Is there a
# >reasonable way to do this data structure, or should I use a messier but
# >guaranteed-to-work set of functions (passing entire array, keeping close
# >track of array values and indices, etc.) as I might in F77?
# >
# >Apologies for the verbosity of this message, but I'm very interested in
# >knowing how the programming tools I use measure up.
# >
# >thanks,
# >Nat Echols
# >
# 
# This seems to work although it's a bit verbose. If you redirect the
# output to a file it takes roughly a second to run on my PC. I got the
# algorithm from a PL/I example in "Computers and Programming" by Francis
# Scheid, Schaum's Outline Series. Any errors are mine not the author's.
# 
# I don't see what the problem is with multi-dimensional arrays and stacks
# in Awk.
# 
# I used gawk 3.1.0 in an MSDOS window under Windows 95.

#towers of hanoi
#objective : move stack 0 to stack 1
#always putting a smaller disc on top of a larger one
#or on an empty stack
#sp[i] = stack pointer for the ith stack = next free space
#stack[i,j] = value of stack i at position j

BEGIN {
  n=8
  for (j=0; j<n; j++) push(0,n-j)
  showstacks()
  hanoi(n,0,1,2)
}

function hanoi(n,a,b,c) {
  if (n==1) {
    move(a,b)
  } else {
    hanoi(n-1,a,c,b)
    move(a,b)
    hanoi(n-1,c,b,a)
  }
}

function push(i,v) { stack[i,sp[i]++]=v }

function pop(i) { return stack[i,--sp[i]] }

function move(i,j) {
  push(j,pop(i))
  showstacks()
}

function showstacks(  i,j) {
  for (i=0; i<=2; i++) {
    printf "%s ", i
    for (j=0; j<sp[i]; j++) printf "%s", stack[i,j]
    print ""
  }
  print ""
}

# -- 
# Alan Linton

