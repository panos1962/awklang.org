From utils Sun Jul 27 10:04:16 1997
Return-Path: <utils>
Resent-Message-Id: <m0wsTvj-000GX2C@skeeve.atl.ga.us>
Resent-From: utils@skeeve (Arnold D. Robbins)
Resent-Date: Sun, 27 Jul 1997 10:04:14 -0400
X-Mailer: Mail User's Shell (7.2.5 10/14/92)
Resent-To: arnold
Return-Path: <dragon!mpeks.tomsk.su!tolik>
Date: Thu, 24 Jul 1997 15:28:50 +0800
From: "Anatoly A. Orehovsky" <tolik@mpeks.tomsk.su>
Message-ID: <199707240728.PAA27978@mpeks.tomsk.su>
To: bug-gnu-utils@prep.ai.mit.edu
CC: arnold@gnu.ai.mit.edu
Subject: About any problems with gawk-3.0.3 install
Status: RO
Content-Length: 2487
X-Lines: 136
X-Display-Position: 0

1. strip binaries in make install wanted
2. I find errata in groupawk.in. getgrent not worked.

I resolved this problem with follow patch:

*** groupawk.in.orig	Thu Jul 24 13:20:14 1997
--- groupawk.in	Thu Jul 24 13:20:30 1997
***************
*** 70,76 ****
  function getgrent()
  {
      _gr_init()
!     if (++gr_count in _gr_bycount)
          return _gr_bycount[_gr_count]
      return ""
  }
--- 70,76 ----
  function getgrent()
  {
      _gr_init()
!     if (++_gr_count in _gr_bycount)
          return _gr_bycount[_gr_count]
      return ""
  }

3. Also, I write simple script, may be interested ?

########################################################################
tree.awk:

#! /usr/bin/awk -f

# tree.10
# print nice tree from find(1) output and alike
# Example:
#	find dir | tree.awk

BEGIN{
	FS = "/";
	i = 0;
	while ( i++ <= ARGC )
	{
		if ( ARGV[i] ~ /(-h)|(-\?)/ ) 
		{
			print "Usage: tree.awk [FS=field-separator] [file ...]";
			EXIT=1;
			exit 1;
		}
	}
}

function fineprint(branches)
{
	gsub(" ","    ", branches);
	gsub("[|]","&   ", branches);
	gsub("`","&---", branches);
	gsub("+","&---", branches);
	return branches;
}

function mktree(number,branches,   tnumber)
{

	if (number > NR)
	{
		return number - 1;
	}
	
	tnumber = number;
	
	while (array["shift", number] < array["shift", tnumber + 1])
	{
		tnumber = mktree(tnumber + 1, branches" ");
		if (tnumber == NR) break;
	}
	
	if (array["shift", number] == array["shift", tnumber + 1])
	{
		array["slip", number] = branches"+";
	}
	
	if ((array["shift", number] > array["shift", tnumber + 1]) \
		|| tnumber == NR)
	{
		array["slip", number] = branches"`";
	}
	
	
	return tnumber;
}

{
	array["shift", NR] = NF;
	array["name", NR] = $(NF);
}

END{
	if (EXIT)
		exit EXIT;
	
	for (i = 1; i <= NR; i++)
	{
		i = mktree(i, "");
	}
	
	for (i = 1; i <= NR; i++)
	{

		if (i > 1)
		{
			lprev = length(array["slip", i - 1]);
			lcurr = length(array["slip", i]);
			if (lprev > lcurr - 1)
				legacy = substr(array["slip", i - 1], 0, \
						lcurr - 1);
			else
				legacy = array["slip", i-1];
			tail = substr(array["slip", i], length(legacy) + 1 , \
					lcurr - length(legacy));
			gsub("+", "|", legacy);
			gsub("`", " ", legacy);
			array["slip", i] = (legacy)(tail);
		}
	
		printf "%s%s\n", fineprint(array["slip", i]), \
				array["name", i];

	}
}
########################################################################

Sorry for my lame english

--
Anatoly A. Orehovsky. AO9-RIPE. AAO1-RIPN


