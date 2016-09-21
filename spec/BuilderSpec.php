<?php
declare(strict_types=1);
namespace spec\Dkplus\Formica;

use DateTime;
use Dkplus\Formica\Builder;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Builder
 */
class BuilderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([], null, DateTime::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Builder::class);
    }

    function it_generates_objects_without_arguments_using_the_constructor()
    {
        $this->beConstructedWith([], null, DateTime::class);
        $this->build()->shouldBeLike(new DateTime());
    }

    function it_generates_objects_with_arguments_using_the_constructor()
    {
        $this->beConstructedWith(['2001-01-01'], null, DateTime::class);
        $this->build()->shouldBeLike(new DateTime('2001-01-01'));
    }

    function it_generates_objects_using_a_named_constructor()
    {
        $this->beConstructedWith(['d.m.Y', '01.01.2001'], 'createFromFormat', DateTime::class);
        $this->build()->shouldBeLike(DateTime::createFromFormat('d.m.Y', '01.01.2001'));
    }

    function it_allows_to_modify_named_arguments_using_with_methods()
    {
        $this->beConstructedWith(['format' => 'm.Y', '01.01.2001'], 'createFromFormat', DateTime::class);
        $this->withFormat('d.m.Y')->shouldBeAnInstanceOf(Builder::class);
        $this->build()->shouldBeLike(DateTime::createFromFormat('d.m.Y', '01.01.2001'));
    }

    function it_allows_to_modify_named_arguments_using_and_methods()
    {
        $this->beConstructedWith(['format' => 'm.Y', '01.01.2001'], 'createFromFormat', DateTime::class);
        $this->andFormat('d.m.Y')->shouldBeAnInstanceOf(Builder::class);
        $this->build()->shouldBeLike(DateTime::createFromFormat('d.m.Y', '01.01.2001'));
    }
}
