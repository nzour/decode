<?php

declare(strict_types=1);

namespace Klimick\PsalmDecode\ObjectDecoder\RuntimeData;

use SimpleXMLElement;
use PhpParser\Node;
use Psalm\Internal\Analyzer\ProjectAnalyzer;
use Psalm\Plugin\EventHandler\AfterExpressionAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterExpressionAnalysisEvent;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;
use Klimick\Decode\RuntimeData;
use Klimick\PsalmDecode\ShapeDecoder\DecoderType;
use Klimick\PsalmDecode\ObjectDecoder\GetGeneralParentClass;
use Fp\Functional\Option\Option;
use function Fp\Evidence\proveOf;
use function Fp\Evidence\proveString;
use function Fp\Evidence\proveTrue;

final class DefinitionCallAnalysis implements PluginEntryPointInterface, AfterExpressionAnalysisInterface
{
    public function __invoke(RegistrationInterface $registration, ?SimpleXMLElement $config = null): void
    {
        $registration->registerHooksFromClass(self::class);
    }

    public static function getClassLikeNames(): array
    {
        return [RuntimeData::class];
    }

    public static function afterExpressionAnalysis(AfterExpressionAnalysisEvent $event): ?bool
    {
        $analysis = Option::do(function() use ($event) {
            $method_call = yield proveOf($event->getExpr(), Node\Expr\StaticCall::class);
            $method_identifier = yield proveOf($method_call->name, Node\Identifier::class);

            yield proveTrue('definition' === $method_identifier->name);

            $class_node = yield proveOf($method_call->class, Node\Name::class);
            $class_name = yield proveString($class_node->getAttribute('resolvedName'));

            if ($class_name === 'self' || $class_name === 'static') {
                $class_name = yield proveString($event->getContext()->self);
            }

            $general_class = yield GetGeneralParentClass::for($class_name, ProjectAnalyzer::$instance->getCodebase());
            yield proveTrue(RuntimeData::class === $general_class);

            $return_type = DecoderType::createObject($class_name);

            $event
                ->getStatementsSource()
                ->getNodeTypeProvider()
                ->setType($method_call, $return_type);
        });

        return $analysis->get();
    }
}