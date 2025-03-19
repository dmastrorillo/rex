<?php

namespace App\Console\Traits;

use Illuminate\Validation\ValidationException;


trait InteractiveCommandTrait
{
    /**
     * Field definition structure
     *
     * @var array<array{
     *  name: string,
     *  option: string,
     *  prompt: string,
     *  description: string,
     *  required: bool
     * }>
     */
    protected $fieldDefinitions = [];

    /**
     * Get field value from option or interactive prompt
     *
     * @param string $optionName The option name to check
     * @param string $prompt The prompt text for interactive input
     * @param bool $required Whether the field is required
     * @param mixed $default Default value if not required and empty
     * @return mixed
     */
    protected function getFieldValue(string $optionName, string $prompt, bool $required = true, $default = null)
    {
        $value = $this->option($optionName);

        if (empty($value) && $required) {
            $value = $this->ask($prompt);
        } elseif (empty($value) && !$required) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Collect all field values from options or prompts
     *
     * @return array
     */
    protected function collectFieldValues()
    {
        $data = [];
        $hasInteractivePrompts = false;

        foreach ($this->fieldDefinitions as $field) {
            $value = $this->option($field['option']);

            if (empty($value) && $field['required']) {
                if (!$hasInteractivePrompts) {
                    $this->line('Please provide the following information:');
                    $hasInteractivePrompts = true;
                }

                $value = $this->ask($field['prompt']);
            }

            $data[$field['name']] = $value;
        }

        if (!$hasInteractivePrompts && !empty($data)) {
            $this->line('Using values provided via command options.');
        }

        return $data;
    }

    /**
     * Handle operation result
     *
     * @param array $result Result with success flag and message
     * @return int Exit code
     */
    protected function handleResult($result, string $successMessage)
    {

        $this->info($successMessage);
        $this->info(json_encode($result, JSON_PRETTY_PRINT));
        return 0;
    }

    /**
     * Handle validation exceptions
     *
     * @param ValidationException $e
     * @return int Exit code
     */
    protected function handleValidationError(ValidationException $e)
    {
        $this->error('Validation failed!');

        foreach ($e->errors() as $field => $errors) {
            foreach ($errors as $error) {
                $this->error("$field: $error");
            }
        }

        return 1;
    }

    /**
     * Handle generic exceptions
     *
     * @param \Exception $e
     * @return int Exit code
     */
    protected function handleGenericException(\Exception $e)
    {
        $this->error('An unexpected error occurred:');
        $this->error($e->getMessage());
        return 1;
    }

    /**
     * Set field definitions for the command
     *
     * @param array $definitions Array of field definitions
     * @return void
     */
    protected function setFieldDefinitions(array $definitions)
    {
        $this->fieldDefinitions = $definitions;
    }
}
