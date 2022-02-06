<?php

namespace Psi\FlexAdmin\Actions;

use Illuminate\Support\Facades\Route;
use Psi\FlexAdmin\Fields\Field;

class Action
{
    public const TYPE_INLINE = 'inline',
        TYPE_GROUPED = 'grouped';

    public const DIVIDER_BEFORE = 'before',
        DIVIDER_AFTER = 'after';

    /**
     * Type of action { inline, grouped }
     *
     * @var string
     */
    protected string $type;

    /**
     * Determines if the action is enabled for this permission, context
     *
     * @var bool
     */
    protected bool $enabled;

    /**
     * Permission for the action , e.g.('users.edit')
     *
     * @var string
     */
    protected string $permission;

    /**
     * Attributes for the action, set on extended action to override default
     *
     * @var array
     */
    protected array $attributes;


    /**
     * Valid contexts for the action
     *
     * @var array
     */
    protected array $contexts;

    /**
     * Adds a divider before or after
     *
     * @var array
     */
    protected array $dividers;

    /**
     * Determines if we should include actions as disabled rather than removed entirely
     * Default: false
     *
     * @var bool
     */
    protected bool $withDisabled = false;

    /**
     * Determines if we should use permissions for the action
     *
     * @var bool
     */
    protected bool $withPermissions = true;

    final public function __construct(public string $slug)
    {
        $this->setDefaults();
    }

    public static function make(string $slug, bool $condition = null)
    {
        $condition = $condition ?? true;

        return $condition ? new static($slug) : null;
    }

    public function withoutPermissions(): self
    {
        $this->withPermissions = false;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function withDisabled(): self
    {
        $this->withDisabled = true;

        return $this;
    }

    public function grouped(bool $condition = true): self
    {
        $this->type = $condition ? self::TYPE_GROUPED : $this->type;
        $this->contexts = [Field::CONTEXT_INDEX => true];     // grouped actions only make sense for indexing

        return $this;
    }

    public function inline(bool $condition = true): self
    {
        $this->type = $condition ? self::TYPE_INLINE : $this->type;

        return $this;
    }

    public function route(string $name, string $method = "get", array $params = []): self
    {
        if (!Route::has($name)) {
            throw new \Exception("Could not find route for name = {$name}. You may need to create an API resource");
        }
        $url = route($name, $params);

        $this->attributes = array_merge($this->attributes, ['external' => false, 'url' => $url, 'target' => '_self', 'asEvent' => false, 'method' => $method]);

        return $this;
    }

    public function url(string $url, $target = "_blank"): self
    {
        $this->attributes = array_merge($this->attributes, ['external' => true, 'url' => $url, 'target' => $target, 'asEvent' => false]);

        return $this;
    }

    public function icon(string $icon): self
    {
        $this->attributes['icon'] = $icon;

        return $this;
    }

    public function title(string $title): self
    {
        $this->attributes['title'] = $title;

        return $this;
    }

    public function permission(string $permission): self
    {
        $this->permission = $permission;

        return $this;
    }

    public function attributes($attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    public function hideFromIndex(bool $condition = true): self
    {
        $this->contexts[Field::CONTEXT_INDEX] = $condition ? false : true;

        return $this;
    }

    public function hideFromDetail(bool $condition = true): self
    {
        $this->contexts[Field::CONTEXT_DETAIL] = $condition ? false : true;

        return $this;
    }

    public function hideFromCreate(bool $condition = true): self
    {
        $this->contexts[Field::CONTEXT_CREATE] = $condition ? false : true;

        return $this;
    }

    public function hideFromEdit(bool $condition = true): self
    {
        $this->contexts[Field::CONTEXT_EDIT] = $condition ? false : true;

        return $this;
    }

    public function divideBefore(bool $condition = true): self
    {
        $this->dividers[self::DIVIDER_BEFORE] = $condition;

        return $this;
    }

    public function divideAfter(bool $condition = true): self
    {
        $this->dividers[self::DIVIDER_AFTER] = $condition;

        return $this;
    }

    public function divideBoth(bool $condition = true): self
    {
        $this->dividers[self::DIVIDER_AFTER] = $condition;
        $this->dividers[self::DIVIDER_BEFORE] = $condition;

        return $this;
    }

    public function confirm(string $confirmText): self
    {
        $this->attributes['confirm'] = true;
        $this->attributes['confirmText'] = $confirmText;

        return $this;
    }

    public function toArray(string $context = Field::CONTEXT_INDEX, mixed $resource = null): array
    {
        $this->enabled =
            $this->displayContext($context) &&             // valid for the context?
            $this->authorized() &&                          // has permission
            $this->canAct($resource);                       // has capability based on resource

        $this->attributes['disabled'] = !$this->enabled;    // disabled attribute reflects true value of enabled

        // If we always want a disabled attribute for the action, we'll enable but set disabled based on this status
        $this->enabled = $this->withDisabled ? true : $this->enabled;

        $action = [
            'enabled' => $this->enabled,
            'type' => $this->type,
            'slug' => $this->slug,
            'attributes' => $this->attributes,
        ];

        return $this->hasDividers() ? $this->withDividers($action) : $action;
    }

    protected function displayContext(string $context): bool
    {
        return $this->contexts[$context] ?? false;
    }

    protected function authorized(): bool
    {
        $this->permission = $this->permission ?? '';

        return $this->withPermissions && !empty($this->permission) ? (auth()->check() ? auth()->user()->can($this->permission) : true) : true;
    }

    protected function canAct(mixed $resource): bool
    {
        return $resource ? (\method_exists($resource, 'canAct') ? $resource->canAct($this->slug) : true) : true;
    }

    protected function setDefaults()
    {
        $this->attributes = $this->attributes ?? $this->defaultAttributes();
        $this->type = $this->type ?? self::TYPE_INLINE;
        $this->dividers = $this->dividers ?? [self::DIVIDER_AFTER => false, self::DIVIDER_BEFORE => false];
        $this->contexts = $this->contexts ?? $this->defaultContexts();
        $this->withPermissions = $this->withPermissions ?? true;
        $this->enabled = $this->enabled ?? true;
    }

    protected function defaultContexts(): array
    {
        return collect(Field::CONTEXTS)->mapWithKeys(fn ($context) => [$context => true])->all();;
    }

    protected function defaultAttributes(): array
    {
        return ['disabled' => false, 'asEvent' => true, 'confirm' => false, 'confirmText' => '', 'divider' => false];
    }

    protected function hasDividers(): bool
    {
        return $this->dividers[self::DIVIDER_AFTER] || $this->dividers[self::DIVIDER_BEFORE];
    }

    protected function withDividers(array $action): array
    {
        return collect([$action])
            ->prepend($this->dividers[self::DIVIDER_BEFORE] ? ['divider' => true] : [])
            ->concat($this->dividers[self::DIVIDER_AFTER] ? [['divider' => true]] : [])
            ->filter()
            ->values()
            ->all();
    }
}
