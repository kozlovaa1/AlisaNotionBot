<?php


class NotionRequest
{
    public string $notionKey;
    public string $notionDatabaseId;
    public string $notionPageId;
    const NOTION_VERSION = 'Notion-Version: 2021-05-13';
    private string $notionBlockId;

    public function __construct()
    {
        $this->notionKey = 'secret_oQp0mySeNtSkrvGxEtObnRxf2kZC0y8L8BuzlMu6cFW';
        $this->notionDatabaseId = 'a7f14d61af20460b81ab35958cee4575';
    }

    /**
     * @param string $notionKey
     */
    public function setNotionKey(string $notionKey): void
    {
        $this->notionKey = $notionKey;
    }

    /**
     * @param string $notionDatabaseId
     */
    public function setNotionDatabaseId(string $notionDatabaseId): void
    {
        $this->notionDatabaseId = $notionDatabaseId;
    }

    /**
     * @param string $notionPageId
     */
    public function setNotionPageId(string $notionPageId): void
    {
        $this->notionPageId = $notionPageId;
    }

    /**
     * @return string
     */
    public function getNotionPageId(): string
    {
        $url = $this->makeRequestUrl('database');
        $data = [];
        return $this->getRequest($data, $url, 'GET')['id'];
    }

    public function addItem(string $value): array
    {
        $data = ["parent" =>
            ["database_id" => $this->notionDatabaseId],
            "properties" => [
                "title" => [
                    "title" => [
                        [
                            "text" => [
                                "content" => $value
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return $this->getRequest($data, 'createPage'); // Send or retrieve data
    }

    public function appendBlock(string $parentBlockId, string $type, string $value): array
    {
        $this->notionBlockId = $parentBlockId;
        $data = [
            "children" => [
                [
                    "object" => "block",
                    "type" => $type,
                    $type => [
                        "text" => [
                            ["type" => "text", "text" => [
                                "content" => $value
                            ]]
                        ]
                    ]
                ]
            ]
        ];
        $url = $this->makeRequestUrl('page');
        return $this->getRequest($data, $url);
    }

    public function makeRequestUrl(string $type): string
    {
        //TODO add exceptions for empty values
        $notionDatabaseId = $this->notionDatabaseId ?? '';
        $notionPageId = $this->notionPageId ?? '';
        $notionBlockId = $this->notionBlockId ?? '';

        $urls = [
            'database' => 'https://api.notion.com/v1/databases/' . $notionDatabaseId,
            'databaseQuery' => 'https://api.notion.com/v1/databases/' . $notionDatabaseId . '/query',
            'createPage' => 'https://api.notion.com/v1/pages/',
            'page' => 'https://api.notion.com/v1/pages/' . $notionPageId,
            'appendBlock' => 'https://api.notion.com/v1/blocks/' . $notionBlockId . '/children',
        ];
        return $urls[$type];

    }

    private function getRequest(array $data, string $url, string $type = 'POST'): array
    {
        $authorization = "Authorization: Bearer " . $this->notionKey;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $type,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                self::NOTION_VERSION,
                $authorization
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

}