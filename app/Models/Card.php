<?php
namespace App\Models;

class Card
{
    private $id;
    private $name;
    private $type;
    private $desc;
    private $atk;
    private $def;
    private $level;
    private $race;
    private $attribute;
    private $archetype;
    private $image_url;
    private $image_url_small;

    public function __construct(array $data)
    {
        // aqui validaciones en '0' o null para evitrar errores
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->type = $data['type'] ?? '';
        $this->desc = $data['desc'] ?? '';
        $this->atk = $data['atk'] ?? 0;
        $this->def = $data['def'] ?? 0;
        $this->level = $data['level'] ?? 0;
        $this->race = $data['race'] ?? '';
        $this->attribute = $data['attribute'] ?? '';
        $this->archetype = $data['archetype'] ?? '';
        
        //  img
        if (isset($data['card_images']) && count($data['card_images']) > 0) {
            $this->image_url = $data['card_images'][0]['image_url'] ?? '';
            $this->image_url_small = $data['card_images'][0]['image_url_small'] ?? '';
        } else {
            $this->image_url = '';
            $this->image_url_small = '';
        }
    }

    /*************************getters***************************************/
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getType(): string { return $this->type; }
    public function getDesc(): string { return $this->desc; }
    public function getAtk(): int { return $this->atk; }
    public function getDef(): int { return $this->def; }
    public function getLevel(): int { return $this->level; }
    public function getRace(): string { return $this->race; }
    public function getAttribute(): string { return $this->attribute; }
    public function getArchetype(): string { return $this->archetype; }
    public function getImageUrl(): string { return $this->image_url; }
    public function getImageUrlSmall(): string { return $this->image_url_small; }

    public function isMonster(): bool
    {
        return strpos($this->type, 'Monster') !== false;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'desc' => $this->desc,
            'atk' => $this->atk,
            'def' => $this->def,
            'level' => $this->level,
            'race' => $this->race,
            'attribute' => $this->attribute,
            'archetype' => $this->archetype,
            'image_url' => $this->image_url,
            'image_url_small' => $this->image_url_small,
            'is_monster' => $this->isMonster()
        ];
    }
}