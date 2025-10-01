<?php
namespace App\Models;

class Pokemon
{
    private $id;
    private $name;
    private $height;
    private $weight;
    private $currentHp;
    private $sprites;
    private $types;
    private $abilities;
    private $stats;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = ucfirst($data['name'] ?? '');
        $this->height = ($data['height'] ?? 0) / 10;
        $this->weight = ($data['weight'] ?? 0) / 10;
        $this->currentHp = $data['stats'][0]['base_stat'] ?? 0;
        $this->sprites = $this->parseSprites($data['sprites'] ?? []);
        $this->types = $this->parseTypes($data['types'] ?? []);
        $this->abilities = $this->parseAbilities($data['abilities'] ?? []);
        $this->stats = $this->parseStats($data['stats'] ?? []);
    }

    private function parseSprites(array $sprites): array
    {
        return [
            'front_default' => $sprites['front_default'] ?? '',
            'back_default' => $sprites['back_default'] ?? '',
            'front_shiny' => $sprites['front_shiny'] ?? '',
            'back_shiny' => $sprites['back_shiny'] ?? ''
        ];
    }

    private function parseTypes(array $types): array
    {
        $parsedTypes = [];
        foreach ($types as $type) {
            $parsedTypes[] = $type['type']['name'];
        }
        return $parsedTypes;
    }

    private function parseAbilities(array $abilities): array
    {
        $parsedAbilities = [];
        foreach ($abilities as $ability) {
            $parsedAbilities[] = [
                'name' => $ability['ability']['name'],
                'is_hidden' => $ability['is_hidden'],
                'slot' => $ability['slot']
            ];
        }
        return $parsedAbilities;
    }

    private function parseStats(array $stats): array
    {
        $parsedStats = [];
        foreach ($stats as $stat) {
            $parsedStats[$stat['stat']['name']] = $stat['base_stat'];
        }
        return $parsedStats;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getHeight(): float { return $this->height; }
    public function getWeight(): float { return $this->weight; }
    public function getCurrentHp(): int { return $this->currentHp; }
    public function getSprites(): array { return $this->sprites; }
    public function getTypes(): array { return $this->types; }
    public function getAbilities(): array { return $this->abilities; }
    public function getStats(): array { return $this->stats; }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'height' => $this->height,
            'weight' => $this->weight,
            'sprites' => $this->sprites,
            'abilities' => $this->abilities,
            'types' => $this->types,
            'stats' => $this->stats
        ];
    }
}