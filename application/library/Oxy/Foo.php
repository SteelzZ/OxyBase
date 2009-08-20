<?php

class Oxy_Foo
{
	private $a;
	protected $b;
	public $c;


	public delagate void DoItHandler(string a);
	/**
	 * Public function
	 *
	 * @event oxy.foo.before
	 * @filter oxy.foo.filter
	 * @event oxy.foo.after
	 *
	 * @return unknown_type
	 */
	public function pubFunc()
	{



		public event DoIT{
			{
				eve
			}
		}

		DoIT();

		// @oxy.foo.eventStart


		// @oxy.foo.eventX

		// @oxy.foo.eventY


		// @oxy.foo.eventZ

			// @oxy.foo.filterA

		// @oxy.foo.eventEnd
	}

	protected function proFunc()
	{
		this.DoIT += new EventHandler(MethodName);

		public void MethodName()
		{

		}


	}

	private function priFunc()
	{

	}
}

Reflection::export(new ReflectionClass('Oxy_Foo'));

$method = new ReflectionMethod('Oxy_Foo', 'pubFunc');
print $method->__toString();
// Print out basic information
printf(
    "===> The %s%s%s%s%s%s%s method '%s' (which is %s)\n" .
    "     declared in %s\n" .
    "     lines %d to %d\n" .
    "     having the modifiers %d[%s]\n",
        $method->isInternal() ? 'internal' : 'user-defined',
        $method->isAbstract() ? ' abstract' : '',
        $method->isFinal() ? ' final' : '',
        $method->isPublic() ? ' public' : '',
        $method->isPrivate() ? ' private' : '',
        $method->isProtected() ? ' protected' : '',
        $method->isStatic() ? ' static' : '',
        $method->getName(),
        $method->isConstructor() ? 'the constructor' : 'a regular method',
        $method->getFileName(),
        $method->getStartLine(),
        $method->getEndline(),
        $method->getModifiers(),
        implode(' ', Reflection::getModifierNames($method->getModifiers()))
);

// Print documentation comment
printf("---> Documentation:\n %s\n", var_export($method->getDocComment(), 1));

// Print static variables if existant
if ($statics= $method->getStaticVariables()) {
    printf("---> Static variables: %s\n", var_export($statics, 1));
}

// Invoke the method
printf("---> Invokation results in: ");
//var_dump($method->invoke(NULL));
?>