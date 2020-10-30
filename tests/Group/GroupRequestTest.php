<?php

namespace Sidigi\LaravelJsonApiRequest\Tests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Sidigi\LaravelJsonApiRequest\Tests\TestCase;
use Sidigi\LaravelJsonApiRequest\Traits\HasGroupFields;

class GroupRequestTest extends TestCase
{
    protected ?FormRequest $formClass = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->formClass = new class() extends FormRequest {
            use HasGroupFields;

            public function rules()
            {
                return $this->groupRules();
            }
        };
    }

    public function test_it_is_ok_with_no_data()
    {
        $this->formRequest(get_class($this->formClass))
            ->assertValidationPassed();
    }

    public function test_it_is_ok_with_null_group()
    {
        $this->formRequest(get_class($this->formClass), [
            'group' => null,
        ])->assertValidationPassed();
    }

    public function test_it_fails_with_group_wrong_type()
    {
        $this->formRequest(get_class($this->formClass), [
            'group' => 1,
        ])->assertValidationErrors(['group'])
            ->assertValidationMessages(['The group must be a string.']);
    }

    public function test_it_can_get_groups()
    {
        $request = (new $this->formClass());

        $groupData = 'fieldOne,fieldTwo.fieldThree';

        $request = $request->merge([
            'group' => $groupData,
        ]);

        $this->assertEquals(
            explode(',', $groupData),
            $request->groups()
        );
    }

    public function test_it_can_has_group()
    {
        $request = (new $this->formClass());

        $groupData = 'fieldOne,fieldTwo.fieldThree';

        $request = $request->merge([
            'group' => $groupData,
        ]);

        $this->assertTrue(
            $request->hasGroup('fieldOne')
        );

        $this->assertTrue(
            $request->hasGroup('fieldTwo')
        );

        $this->assertTrue(
            $request->hasGroup('fieldThree')
        );

        $this->assertTrue(
            $request->hasGroup('fieldTwo.fieldThree')
        );
    }
}
