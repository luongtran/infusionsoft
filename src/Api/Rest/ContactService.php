<?php namespace Infusionsoft\Api\Rest;

use Infusionsoft\Api\Rest\Traits\CannotSync;
use Infusionsoft\Infusionsoft;
use Infusionsoft\InfusionsoftException;

class ContactService extends RestModel
{
    use CannotSync;

    public $full_url = 'https://api.infusionsoft.com/crm/rest/v1/contacts';

    protected $updateVerb = 'patch';

    public $return_key = 'contacts';

    public function __construct(Infusionsoft $client)
    {
        parent::__construct($client);
    }


    public function tags()
    {
        $data = $this->client->restfulRequest('get', $this->getFullUrl($this->id . '/tags'));
        $this->fill($data);

        return $this;
    }

    public function emails()
    {
        $data = $this->client->restfulRequest('get', $this->getFullUrl($this->id . '/emails'));
        $this->fill($data);

        return $this;
    }

    public function addTags($id, $tagIds)
    {
        if ( ! is_array($tagIds)) {
            throw new InfusionsoftException('Must be an array of tag ids');
        } elseif (count($tagIds) > 100) {
            throw new InfusionsoftException('A maximum of 100 tag ids can be added at once');
        }

        $tags         = new \stdClass;
        $tags->tagIds = $tagIds;

        $response = $this->client->restfulRequest('post', $this->getFullUrl($id . '/tags'), $tags);

        return $response;

    }

    public function create(array $attributes = [], $dupCheck = false)
    {
        $this->mock($attributes);
        if ($dupCheck) {
            $data = $this->client->restfulRequest('put', $this->getFullUrl($this->id), (array)$this->toArray());
            $this->fill($data);
        } else {
            $this->save();
        }

        return $this;
    }

    public function removeTags($id, $tagIds)
    {
        if ( ! is_array($tagIds)) {
            throw new InfusionsoftException('Must be an array of tag ids');
        } elseif (count($tagIds) > 100) {
            throw new InfusionsoftException('A maximum of 100 tag ids can be deleted at once');
        }

        $tagIds = ['ids' => implode(",", $tagIds)];

        $response = $this->client->restfulRequest('delete', $this->getFullUrl($id . '/tags'), $tagIds);

        return $response;

    }

    public function retrieveCreditCards($contactId) {
        $data = $this->client->restfulRequest('get', $this->getFullUrl($contactId).'/creditCards');
        $this->fill($data);
        return $this->getAttributes();
    }

    public function updateCustomFields($contactId, $customFields) {
        $data = $this->client->restfulRequest('patch', $this->getFullUrl($contactId), $customFields);
        return $this->fill($data);
    }

}