# BattleGame-PHPCLI
A PHP command line application that simulates a battle between two combatants

# Combatant	Types And Properties
There	are	3	types	of	combatant:	swordsman,	brute	and	grappler.	Each	type	has	strengths	and	
weaknesses.	At	the	start	of	a	battle,	when	combatants	are	created,	every	property	must	be	
randomly	determined	between	the	maximum	and	minimum	values	allowed	for	that	type.

Each	combatant	has	the	following	properties:

Combatant | Health | Strength | Defense | Speed | Luck |
--- | --- | --- | --- |--- |--- |
Swordsman | 40-60 | 60-70 | 20-30 | 90-100 | 0.3-0.5 | 
Brute | 90-100 | 65-75 | 40-50 | 40-65 | 0.3-0.35 |
Grappler | 60-100 | 75-80 | 35-40 | 60-80 | 0.3-04 |

Property | Use | Value type
--- | ---| ---|
Name | Name	of	the	combatant | String,	30	chars	or	less
Health | Amount	of health	remaining | Whole	number,	0	to	100
Strength | Damage	that	is	done	upon	attack | Whole	number,	0	to	100
Defense | Damage	reduction	during	defense	of	an	attack | Whole	number,	0	to	100
Speed | Determines	attack	order | Whole	number,	0	to	100
Luck | Affects	ability	to	dodge	an	attack | Decimal,	0	to	1


# Battle/Game	Flow
The	program	runs	on	the	command	line.		When	the	program	starts,	it	asks	for	the	names	of	two	
combatants	and	assigns	them	a	type	of	battler	at	random.		The	properties	are	then	determined	
randomly	for	each	fighter	as	above.	
-The	program	runs	the	battle	simulation	and	outputs	a	line	of	text	each	turn	explaining	what	
happened	that	round	until	either	one	runs	out	of	health	or	30	turns	pass	without	a	winner	
being	declared.

*The	speed	of	the	combatants	determines	which	one	will	attack	first,	
 *if	two	combatants	have	the	same	speed	the	one	with	the	lower	defense	should	go	first	and	
 *if	those	are	the	same then combatant	one	should	go	first.	
 
*The	combatants	then	attack	one	at	a	time	until	the	end	of	the	battle.	
 *The	damage	dealt	by	the	attacker	is	determined	with	the	following	calculation:
  *Damage	=	Attacker	strength	–	Defenders	Defense
   *The	damage	is	subtracted	from	the	defenders	health.	
 *If	a	fighter’s	health	drops	to	0	they	lose	the	fight.	If	the	fight	has	not	finished	after	30	rounds	a	draw	is	declared.

*Every	time	a	defender	is	attacked	there	is	a	small	chance	the	attacker	misses.	The	chance	of	an	attack	missing	is	denoted	by	the	defenders	luck	property	(0.3	=	30%).	

# Special	Skills

Each	type	of	battler	has	a	special	skill:
 *Swordsman –	Lucky	Strike
   *With	each	attack	there	is	a	5%	chance	of	their	strength	doubling	for	that	attack.
 *Brute –	Stunning	blow
   *With	each	attack	there	is	a 2%	chance	of	stunning	the	enemy,	causing	them	to	miss	their	next	attack.
 *Grappler –	Counter	Attack
   *When	a	grappler	evades	an	attack	their	opponent	is	dealt	10	damage.
   
*When	a	battle	ends,	the	program	should	declare	the	winner	by	name,	or	announce	the	result	as	a	draw.
