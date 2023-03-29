<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('icon' , $this->showIcon(...), ['is_safe' => ['html']]),
            new TwigFunction('menu_active', $this->menuActive(...), ['is_safe' => ['html'], 'needs_context' => true]),
        ];
    }

    /**
     * Affiche une icône
     * @param string $icon
     * @param string|null $size
     * @param string|null $attrs
     * @return string
     */
    public function showIcon(string $icon, ?string $size = null, ?string $attrs = null): string
    {
        $attributes = '';
        if ($size) {
            $attributes = 'la-'. $size;
        }

        if ($attrs) {
            $attributes .= ' ' . $attrs;
        }

        return <<<HTML
            <i class="las la-{$icon}  {$attributes}"></i>
        HTML;
    }

    /**
     * Ajoute une classe active au menu en fonction de la route
     * @param array $context
     * @param string $route
     * @return string
     */
    public function menuActive(array $context, string $route): string
    {
        $active = '';
        $request = $context['app']->getRequest();
        $currentRoute = $request->get('_route');

        if ($currentRoute === $route) {
            $active = 'active';
        }

        return $active;
    }

}