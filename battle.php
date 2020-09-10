<?php


class ConsoleQuestion
{

	function readline()
	{
		return rtrim(fgets(STDIN));
	}
}



class setupGame
{
	protected $player1;
	protected $player2;
	protected $winner = '';
	public $numTurns = 0;
	public $endGame = false;
	protected $playerNames = [
		'Swordsman',
		'Brute',
		'Grappler'
	];

	function checkPlayer($player)
	{
		if (class_exists($player)) {
			return true;
		}
		else {
			false;
		}
	}

	function setPlayer1($player1)
	{
		$this->player1 = $player1;
	}


	function setPlayer2($player2)
	{
		$this->player2 = $player2;
	}

	public function showPlayerNames()
	{
		$nameString = implode(', ', $this->playerNames);

		return $nameString;
	}


	/**
	 * Check the string name given for the fighter
	 *
	 * @param $player
	 * @return mixed
	 */
	function createPlayer($player)
	{
		if (class_exists($player)) {
			return new $player();
		}
		else
		{
			false;
		}
	}




	/**
	 * Method for external script to call and check during progress of game
	 */
	public function getPlayer1()
	{
		return $this->player1;
	}




	/**
	 * Method for external script to call and check during progress of game
	 */
	public function getPlayer2()
	{
		return $this->player2;
	}


	public function terminateGame()
	{
		$this->endGame = true;
	}



	/**
	 * The rules are:
	 * A fighter can only hit an opponent if he is not stunned
	 * There's a 5% chance a fighter can hit and miss if the victim is lucky
	 * If the lucky victim is a Grappler, the fighter gets hit by a counter attack and suffers a damage of 10
	 * There's a 5% chance a Swordsman may get a lucky strike and hit his opponent with twice his strength for once.
	 * A Brute fighter has a small (2%) chance of stunning his opponent whereby they are not able to hit on the next turn.
	 *
	 * @param $hitter the fighter hitting
	 * @param $victim the fighter receiving the blow
	 */
	function hit(&$hitter, &$victim)
	{
		if (($this->getPlayer1()->getHealth() < 0) || ($this->getPlayer2()->getHealth() < 0) || ($this->endGame == true))
		{
			return false;
		}

		$feedbackString = 'This is turn ' . $this->numTurns . PHP_EOL;
		$feedbackString .= 'Health of ' . $victim->getName() . ' was ' . $victim->getHealth() . PHP_EOL;
		//A fighter can only hit the opponent if they are not stunned
		if (!$hitter->isStunned()) {

			//Lets see how lucky the victim is
			if ($victim->getLuck()) {
				//if the lucky victim is a Grappler then the assailant will suffer a damage of 10
				if ($victim instanceof Grappler)
				{
					$grapplerCounterAttack = 10;
					$hitter->setHealth($hitter->getHealth() - $grapplerCounterAttack);
					$feedbackString .= $hitter->getName() . ' just tried to hit ' . $victim->getName() . ' but missed ' . PHP_EOL;
					$feedbackString .= 'But the Grappler hit the him back with a Counter causing a damage of ' . $grapplerCounterAttack . PHP_EOL;
					$feedbackString .= 'So the Health of ' . $victim->getName() . ' IS still ' . $victim->getHealth() . PHP_EOL;
				}
				else
				{
					//The lucky victim is not a Grappler
					$feedbackString .= $hitter->getName() . ' just tried to hit ' . $victim->getName() . ' but missed ' . PHP_EOL;
					$feedbackString .= 'So the Health of ' . $victim->getName() . ' IS still ' . $victim->getHealth() . PHP_EOL;
				}
			}
			else {
				//A Swordsman fighter has a lucky strike, so work it out
				if ($hitter instanceof Swordsman) {
					$luckyStrike = rand(1, 100);
					if ($luckyStrike <= 5) {
						$doubleStrength = $hitter->getStrength() * 2;
						$damage = $doubleStrength - $victim->getDefense();
						$victim->setHealth($victim->getHealth() - $damage);

						$feedbackString .= $hitter->getName() . ' just hit  '. $victim->getName(). ' with a LUCKY STRIKE (double) and caused a Damage of ' . $damage . PHP_EOL;
						$feedbackString .= 'NOW the Health of ' . $victim->getName() . ' IS ' . $victim->getHealth() . PHP_EOL;
					}
					else {
						//Swordsman takes a normal shot
						$damage = $hitter->getStrength() - $victim->getDefense();
						$victim->setHealth($victim->getHealth() - $damage);

						$feedbackString .= $hitter->getName() . ' just hit ' . $victim->getName() . ' and caused a Damage of ' . $damage . PHP_EOL;
						$feedbackString .= 'NOW the Health of ' . $victim->getName() . ' IS ' . $victim->getHealth() . PHP_EOL;
					}
				}
				//A Brute fighter has a chance to stun her adversary, so work it out
				elseif ($hitter instanceof Brute) {
					$checkStunChance = rand(1, 100);
					if ($checkStunChance <= 2) {
						$damage = $hitter->getStrength() - $victim->getDefense();
						$victim->setHealth($victim->getHealth() - $damage);

						//stun the victim
						$victim->setStunned(true);

						$feedbackString .= $hitter->getName() . ' just hit and stunned ' . $victim->getName() . ' and caused a Damage of ' . $damage . PHP_EOL;
						$feedbackString .= 'NOW the Health of ' . $victim->getName() . ' IS ' . $victim->getHealth() . PHP_EOL;
					}
					else {
						//The Brute takes a non-stunning shot
						$damage = $hitter->getStrength() - $victim->getDefense();
						$victim->setHealth($victim->getHealth() - $damage);

						$feedbackString .= $hitter->getName() . ' just hit ' . $victim->getName() . ' and caused a Damage of ' . $damage . PHP_EOL;
						$feedbackString .= 'NOW the Health of ' . $victim->getName() . ' IS ' . $victim->getHealth() . PHP_EOL;
					}
				}
				else {
					//This hitter is neither a Swordsman nor a Brute
					$damage = $hitter->getStrength() - $victim->getDefense();
					$victim->setHealth($victim->getHealth() - $damage);

					$feedbackString .= $hitter->getName() . ' just hit ' . $victim->getName() . ' and caused a Damage of ' . $damage . PHP_EOL;
					$feedbackString .= 'NOW the Health of ' . $victim->getName() . ' IS ' . $victim->getHealth() . PHP_EOL;
				}

			}
		}
		else
		{
			//This fighter is stunned and has missed her turn, so undo the stunning
			$hitter->setStunned(false);
			$feedbackString .= $hitter->getName() . ' was stunned and so could not hit ' . $victim->getName() . PHP_EOL;
		}

		echo $feedbackString;

		if (($victim->getHealth() <= 0) || ($hitter->getHealth() <= 0) || ($this->numTurns >= 30))
		{
			$this->terminateGame();
		}

	}



	/**
	 * manages the fighting turns
	 */
	function fight()
	{
		//increment the turn
		$this->numTurns++;
		echo PHP_EOL;

		//who attacks first
		if ($this->player1->getSpeed() > $this->player2->getSpeed())
		{
			//player1 hits first
			$this->hit($this->player1, $this->player2);
			$this->hit($this->player2, $this->player1);
		}
		elseif ($this->player2->getSpeed() > $this->player1->getSpeed())
		{
			//player2 hits first
			$this->hit($this->player2, $this->player1);
			$this->hit($this->player1, $this->player2);
		}
		//both fighters have equal speed, so check who has the lowest defense and let them go first
		elseif ($this->player1->getDefense() < $this->player2->getDefense())
		{
			//player1 hits first
			$this->hit($this->player1, $this->player2);
			$this->hit($this->player2, $this->player1);
		}
		elseif ($this->player2->getDefense() < $this->player1->getDefense())
		{
			//player2 hits first
			$this->hit($this->player2, $this->player1);
			$this->hit($this->player1, $this->player2);
		}
		//both have equal speed and equal defense, so fighter1 goes first
		else
		{
			//player1 hits first
			$this->hit($this->player1, $this->player2);
			$this->hit($this->player2, $this->player1);
		}
	}

	public function showScores()
	{
		$winner = 'Draw';

		if ($this->player1->getHealth() > $this->player2->getHealth())
		{
			$winner = $this->player1->getName();
		}
		if ($this->player2->getHealth() > $this->player1->getHealth())
		{
			$winner = $this->player2->getName();
		}

		$scoreString = "The WINNER IS: ".$winner.". - Scores: ".$this->player1->getName()." health: ".$this->player1->getHealth()." ".$this->player2->getName()." health: ".$this->player2->getHealth().PHP_EOL;

		return $scoreString;
	}

}




//player types
class Swordsman
{
	protected $health = 0;
	protected $strength = 0;
	protected $defense = 0;
	protected $speed = 0;
	protected $luck = 0;
	protected $name = 'Swordsman';
	protected $stunned = false; //fighter can be stunned by an attack from the Brute

	public function __construct()
	{
		$this->health = rand(40, 60);
		$this->strength = rand(60, 70);
		$this->defense = rand(20, 30);
		$this->speed = rand(90, 100);
		$this->luck = mt_rand(0.3*100, 0.5*100);
	}

	public function getHealth()
	{
		return $this->health;
	}

	public function setHealth($health)
	{
		$this->health = $health;
	}

	public function getStrength()
	{
		return $this->strength;
	}

	public function getDefense()
	{
		return $this->defense;
	}

	public function getSpeed()
	{
		return $this->speed;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getLuck()
	{
		$missChance = rand(1, 100);
		if ($missChance <= $this->luck)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function setStunned($bool)
	{
		$this->stunned = $bool;
	}

	public function isStunned()
	{
		return $this->stunned;
	}

}




class Brute
{
	protected $health = 0;
	protected $strength = 0;
	protected $defense = 0;
	protected $speed = 0;
	protected $luck = 0;
	protected $name = 'Brute';
	protected $stunned = false;

	public function __construct()
	{
		$this->health = rand(90, 100);
		$this->strength = rand(65, 75);
		$this->defense = rand(40, 50);
		$this->speed = rand(40, 65);;
		$this->luck = mt_rand(0.3*100, 0.35*100);

	}

	public function getHealth()
	{
		return $this->health;
	}

	public function setHealth($health)
	{
		$this->health = $health;
	}

	public function getStrength()
	{
		return $this->strength;
	}

	public function getDefense()
	{
		return $this->defense;
	}

	public function getSpeed()
	{
		return $this->speed;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getLuck()
	{
		$missChance = rand(1, 100);
		if ($missChance <= $this->luck)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	 * Will never be used anyway, as a Brute stuns but cannot be stunned.
	 * @param $bool
	 */
	public function setStunned($bool)
	{
		$this->stunned = $bool;
	}

	public function isStunned()
	{
		return $this->stunned;
	}
}



class Grappler
{
	protected $health = 0;
	protected $strength = 0;
	protected $defense = 0;
	protected $speed = 0;
	protected $luck = 0;
	protected $name = 'Grappler';
	protected $stunned = false; //fighter can be stunned by an attack from the Brute

	public function __construct()
	{
		$this->health = rand(60, 100);
		$this->strength = rand(75, 80);
		$this->defense = rand(35, 40);
		$this->speed = rand(60, 80);
		$this->luck = mt_rand(0.3*100, 0.4*100);
	}

	public function getHealth()
	{
		return $this->health;
	}

	public function setHealth($health)
	{
		$this->health = $health;
	}

	public function getStrength()
	{
		return $this->strength;
	}

	public function getDefense()
	{
		return $this->defense;
	}

	public function getSpeed()
	{
		return $this->speed;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getLuck()
	{
		$missChance = rand(1, 100);
		if ($missChance <= $this->luck)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function setStunned($bool)
	{
		$this->stunned = $bool;
	}

	public function isStunned()
	{
		return $this->stunned;
	}
}



$line = new ConsoleQuestion();
$player1Prompt  = "Enter name of player 1: ";
$player2Prompt = "Enter name of player 2: ";

echo $player1Prompt;
$name1 = $line->readline();

echo $player2Prompt;
$name2 = $line->readline();

//get players
$setup = new setupGame();
if ($setup->checkPlayer($name1))
{
	$player1 = $setup->createPlayer($name1);
}
else
{
	echo "The player $name1 does not exist! It must be one of the following: ".$setup->showPlayerNames().' ';
	exit();
}

if ($setup->checkPlayer($name2))
{
	$player2 = $setup->createPlayer($name2);
}
else
{
	echo "The player $name2 does not exist! It must be one of the following: ".$setup->showPlayerNames().' ';
	exit();
}

//set players in the battle
$setup->setPlayer1($player1);
$setup->setPlayer2($player2);


//start game in a loop-a turn at every iteration, while healths are greater than 0
while (($setup->getPlayer1()->getHealth() > 0) && ($setup->getPlayer2()->getHealth() > 0) && ($setup->endGame == false))
{
	$setup->fight();
}

echo $setup->showScores();
exit();



