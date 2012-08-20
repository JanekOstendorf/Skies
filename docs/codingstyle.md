Skies coding style
==================

PHP (and Javascript)
--------------------

###Indentation (Brace style)

In Skies a mix of a few coding styles is used. See the example below for reference.
Example:

```

<?php
class Classname {

    protected $protectedVar;
    protected $anotherVar;

    public function __construct($var) {

        if($var == 1) {

            for($i = 0; $i < 2; $i++) {
                $t = time();
                $var += $i;
            }

            $var = 2;

        }
        else
            $var++;

        while($var < 10) {
            print($var);
            $var++;
        }
    }
}
?>
```

###Spacing

We use _4 spaces_ to indent subordinated code.
Between the function- or statement names and their brackets there are no spaces.
Operators (except "++", "--", "::", "->" or ".") have spaces before and after.
Example:

```
<?php
function example($var) {

	// Comment
	if(is_int($var)) {

		$var++;

		if($var > 10)
			$var--;
		elseif($var <= 10)
			$var += 20;

		$var = max(++$var, 29);

	}

	return $var;

}
?>
```

###Naming

In SilexBoard variable-, function- and methodnames are lowerCamelCase. The first letter is lower case. `$varName`, `iAmAFunction($argumentNo1, $argumentNo2)`
Class-, trait- and interfacenames are UpperCamelCase, with a upper case first letter. `FooBar`, `TestClass`
Constants are uppercase with an underscore as word-delimiter. `I_AM_A_CONSTANT`, `CURRENT_TIME`
Example:

```
<?php
define('MY_CONSTANT', true);

class ExampleClass {

	public static function doSomething($var) {
		for($i = 0; $i < 1; $i++)
			echo 'Whoooooooo';
		return 'Year: '.$var;
	}

}

$varOutput = ExampleClass::doSomething(2012);

if(MY_CONSTANT)
    echo $varOutput;
?>
```

###Quotes

You should always use single quotes (`'`), except you need special escape characters.
Heredoc and Nowdoc should not be used. Instead, close the php tag and print the string with linebreaks this way.
Example:

```
<?php

// Normal strings
$string = 'string';
$stringWithNewLine = 'string'."\n";

// Long strings
?>
Hi, I am an output!
And another line ..
And yet another line ..
<?php

// Do _NOT_ use this:
echo <<<HEREDOC
Hi, I am an output!
And another line ..
And yet another line ..
HEREDOC;

?>
```

HTML
----------
Example:

```
<div class="class_name">
    <div class="another_class_name"><?=$foo['bar']?></div>
</div>
```

CSS
---
Example:

```
.class_name {
    color: #dedede;
    background: #333333;
}
.class_name .another_class_name {
    background: white;
    border-radius: 10px 20px 5px 2px;
    padding: 10px 5px;
}
```