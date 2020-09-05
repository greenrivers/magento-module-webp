<?php

namespace Unexpected\Webp\Plugin\Filter;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Filter\DirectiveProcessor\DependDirective;
use Magento\Framework\Filter\DirectiveProcessor\IfDirective;
use Magento\Framework\Filter\DirectiveProcessor\LegacyDirective;
use Magento\Framework\Filter\DirectiveProcessor\TemplateDirective;
use Magento\Framework\Filter\DirectiveProcessorInterface;
use Magento\Framework\Filter\Template as Subject;

class Template
{
    /** @var Subject */
    private $subject;

    /** @var DirectiveProcessorInterface[] */
    private $directiveProcessors;

    /** @var array */
    private $afterFilterCallbacks = [];

    /** @var array */
    private $templateVars = [];

    /**
     * Template constructor.
     * @param Subject $subject
     * @param DirectiveProcessorInterface[] $directiveProcessors
     * @param array $variables
     */
    public function __construct(Subject $subject, $directiveProcessors = [], $variables = [])
    {
        $this->directiveProcessors = $directiveProcessors;
        $this->subject = $subject;
        $this->subject->setVariables($variables);

        if (empty($directiveProcessors)) {
            $this->directiveProcessors = [
                'depend' => ObjectManager::getInstance()->get(DependDirective::class),
                'if' => ObjectManager::getInstance()->get(IfDirective::class),
                'template' => ObjectManager::getInstance()->get(TemplateDirective::class),
                'legacy' => ObjectManager::getInstance()->get(LegacyDirective::class),
            ];
        }
    }

    public function aroundFilter(Subject $subject, callable $proceed, $value)
    {
        foreach ($this->directiveProcessors as $directiveProcessor) {
            if (!$directiveProcessor instanceof DirectiveProcessorInterface) {
                throw new \InvalidArgumentException(
                    'Directive processors must implement ' . DirectiveProcessorInterface::class
                );
            }

            if (preg_match_all($directiveProcessor->getRegularExpression(), $value, $constructions, PREG_SET_ORDER)) {
                foreach ($constructions as $construction) {
                    $replacedValue = $directiveProcessor->process($construction, $subject, $this->templateVars);

                    $value = str_replace($construction[0], $replacedValue, $value);
                }
            }
        }

        $value = $this->afterFilter($value);

        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    private function afterFilter($value)
    {
        foreach ($this->afterFilterCallbacks as $callback) {
            $value = call_user_func($callback, $value);
        }
        // Since a single instance of this class can be used to filter content multiple times, reset callbacks to
        // prevent callbacks running for unrelated content (e.g., email subject and email body)
        $this->resetAfterFilterCallbacks();
        return $value;
    }

    /**
     * @return $this
     */
    private function resetAfterFilterCallbacks()
    {
        $this->afterFilterCallbacks = [];
        return $this;
    }
}