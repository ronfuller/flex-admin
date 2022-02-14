<?php

namespace Psi\FlexAdmin\Collections;

use Psi\FlexAdmin\Resources\Resource;

trait FlexCache
{
    private string $cacheKey;

    /**
     * Disables caching meta columns data
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function withoutCache(): self
    {
        $this->shouldCacheMeta = false;

        return $this;
    }

    protected function getCollectionMeta(Resource $resource): array
    {
        // Determine if we are caching
        return $this->shouldCacheMeta && $this->hasMetaCache() ? $this->getCollectionMetaFromCache() : $this->getCollectionMetaFromSource($resource);
    }

    protected function getCollectionMetaFromSource(Resource $resource): array
    {
        $meta = $resource->withContext($this->context)->toMeta($this->flexModel);

        if ($this->shouldCacheMeta) {
            session()->put($this->getCacheKey(), $meta);
        }

        return $meta;
    }

    protected function getCollectionMetaFromCache(): array
    {
        return session()->get($this->getCacheKey());
    }

    protected function hasMetaCache(): bool
    {
        return session()->has($this->getCacheKey());
    }

    private function getCacheKey(): string
    {
        return $this->cacheKey ?? str(request()->url() . "-" . get_class($this->flexModel) . "-" . $this->context)->replaceMatches("/[^A-Za-z0-9]++/", "-")->lower();
    }
}
