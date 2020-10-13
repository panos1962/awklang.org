BEGIN {
	setup()
	create_deck(deck)
	shuffle_cards(deck)
	deal_cards(deck, hands)
	print_hands(hands)
}

function setup() {
	suit_list["C"] = "Clubs"
	suit_list["S"] = "Spades"
	suit_list["D"] = "Diamonds"
	suit_list["H"] = "Hearts"

	rank_list["2"] = "Two"
	rank_list["3"] = "Three"
	rank_list["4"] = "Four"
	rank_list["5"] = "Five"
	rank_list["6"] = "Six"
	rank_list["7"] = "Seven"
	rank_list["8"] = "Eight"
	rank_list["9"] = "Nine"
	rank_list["T"] = "Ten"
	rank_list["J"] = "Jack"
	rank_list["Q"] = "Queen"
	rank_list["K"] = "King"
	rank_list["A"] = "Ace"

	# There exist two functions for printing hands. Function
	# "print_hand_text" prints hands as text, while function
	# "print_hand_html" creates HTML for displaying hands in
	# a web page.

	print_hand = html ? "print_hand_html" : "print_hand_text"

	srand()
}

# Function "create_deck" accepts a cards array as an argument and
# fills it with cards. Cards are indexed from 1 to 52, while index
# 0 contains cards' count (52). Each card is represented as a string
# "SR", where "S" is the suit and "R" is the rank, e.g. "S7" is the
# Seven of Spades, "HT" is the Ten of Hearts and "DQ" is the Queen
# of Diamonds.

function create_deck(deck,		suit, rank) {
	delete deck

	for (suit in suit_list) {
		for (rank in rank_list) {
			deck[0]++
			deck[deck[0]] = suit rank
		}
	}
}

# Function "shuffle_cards" shuffles the deck passed as parameter.
# We can pass the shuffle count as second parameter, which by deafult
# is 10 times the deck's cards count.

function shuffle_cards(deck, n,		i1, i2, t) {
	if (!n)
	n = deck[0] * 10

	while (n--) {
		i1 = int(rand() * deck[0]) + 1
		i2 = int(rand() * deck[0]) + 1

		t = deck[i1]
		deck[i1] = deck[i2]
		deck[i2] = t
	}
}

# Function "deal_cards" deals cards from the (shuffled) deck passed as
# first parameter. The second parameter is an array of hands, where
# each hand is an array of cards indexed from 1 to the hands' count;
# index 0 of each hand contains the hand's cards' count.

function deal_cards(deck, hands, n,		i, j) {
	delete hands

	# By default 5 cards are dealt.

	if (!n)
	n = 5

	for (i = 1; i <= n_players; i++) {
		hands[i][0] = n
		for (j = 1; j <= n; j++) {
			hands[i][j] = pop_card(deck)
		}
	}
}

# Function "pop_card" pops a card from the hand passed as first
# parameter. The whole deck could be considered as "hand".
# The popped card is returned, while the cards' count (index 0) is
# decremented by one. Usually the hand passed is the whole deck.

function pop_card(x,		card) {
	if (!x[0]) {
		print "no more cards" >"/dev/stderr"
		exit(1)
	}

	card = x[x[0]]
	x[0]--
	
	return card
}

# Function "print_hands" prints the hands of all players. There exist
# two print functions, one for printing hands as text and the other
# for printing hands as HTML elements.

function print_hands(hands,			i) {
	for (i = 1; i <= n_players; i++) {
		@print_hand(hands[i])
	}
}

# Function "print_hand_text" prints the passed hand as a string.

function print_hand_text(hand,		i) {
	for (i = 1; i <= hand[0]; i++) {
		printf hand[i]
	}

	print ""
}

# Function "print_hand_html" prints the passed hand as HTML <div> element
# containing the card images.

function print_hand_html(hand,		i) {
	print "<div>"

	for (i = 1; i <= hand[0]; i++) {
		print "<img src=\"../image/cards/" hand[i] ".png\">"
	}

	print "</div>"
}
