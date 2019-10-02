<?php

namespace Implement\Model;

class Model implements \Canvass\Contract\Model
{
    protected $data;

    protected $table;

    protected $columns = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    protected function getDataFromFile(): ?array
    {
        $file = $this->getFilePath();

        if (! file_exists($file)) {
            throw new ModelNotFoundException('File not found');
        }

        $data = json_decode(file_get_contents($file), true);

        if (empty($data)) {
            return [];
        }

        return $data;
    }

    protected function getFilePath(): string
    {
        return CANVASS_DATA_DIR . "/{$this->table}.json";
    }

    public function find($id, $owner_id = null)
    {
        $data = $this->getDataFromFile();

        if (
            ! isset($data[$id]) ||
            (null !== $owner_id && $owner_id != $data[$id]['owner_id'])
        ) {
            throw new ModelNotFoundException('Model not found');
        }

        return new static($data[$id]);
    }

    public function save()
    {
        try {
            $data = $this->getDataFromFile();
        } catch (\InvalidArgumentException $e) {
            $data = [];
        }

        if (empty($this->data['id'])) {
            $this->data['id'] = count($data) + 1;
        }

        $data[$this->data['id']] = array_merge($this->columns, $this->data);

        return $this->putData($data);
    }

    public function delete()
    {
        $data = $this->getDataFromFile();

        unset($data[$this->data['id']]);

        return $this->putData($data);
    }

    private function putData($data)
    {
        return file_put_contents($this->getFilePath(), json_encode($data));
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function getData($key)
    {
        return $this->data[$key] ?? '';
    }

    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function toArray()
    {
        return array_merge($this->columns, $this->data);
    }

    public static function clearAll(): void
    {
        $files = array_diff(
            scandir(CANVASS_DATA_DIR),
            ['.', '..', 'readme.md']
        );

        foreach ($files as $file) {
            unlink(CANVASS_DATA_DIR . "/{$file}");
        }

        foreach (['form', 'field'] as $file) {
            file_put_contents(CANVASS_DATA_DIR . "/{$file}.json", '{}');
        }
    }
}
