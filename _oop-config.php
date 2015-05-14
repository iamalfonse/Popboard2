<?php
//example of calling a class/object inside of a class
class snake {
	function eat_bugs(){
		echo "He eats bugs";

		$dog = new dog();
		$dog->eat_snake();

	}
}
class dog {
	function eat_snake(){
		echo "The dog ate the snake!";
	}
}

// instantiate the variable $my_snake to the snake() object
$my_snake = new snake();
// then call the method 'eat_bugs()'
$my_snake->eat_bugs();



// ----------- inheritance-----------
class person {
	var $name = "Stefan";
	var $weight = "178lbs";
	public $height = "7ft";
	protected $social_insurance;
	private $pin_number;

	protected function set_name($newname){
		if($newname != "Jimmy Two Guns"){
			$this->name = strtoupper($newname);
		}
		
	}

	function get_name(){
		return $this->name;
	}

	function get_weight(){
		return $this->weight;
	}

	function get_height(){
		return $this->height;
	}

}


// $stefan = new person();
// echo $stefan->get_name();
echo "<br />";
// $stefan->set_name("Adam");
// echo $stefan->get_name();
echo "<br />";
// echo $stefan->pin_number; //shows a fatal error


// extending/inheriting from another class
// 'extends' is the keyword that enables inheritance

class employee extends person {
	function __construct($employee_name){
		$this->set_name($employee_name);
	}

	protected function set_name($newname) {
		if($newname == "Stefan Sucks"){
			$this->name = $newname;
		}else if ($newname == "Johnny Fingers"){

			// how to access the original method from 'person' class
			person::set_name($newname);

			// how to access the method from the parent of this class
			person::set_name($newname);
		}
	}
}

// instantiate $james variable as a new object of 'employee'(the child class) from the parent 'person' class
$james = new employee("Johnny Fingers");
echo "<br />His name is ". $james->get_name()." and he weights ".$james->get_weight()." and is ".$james->get_height()." tall";




?>