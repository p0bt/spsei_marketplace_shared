<?php
namespace SpseiMarketplace\Models;

use SpseiMarketplace\Core\Database;

class Message extends BaseModel
{
    public function post($data)
    {
        return Database::query("INSERT INTO `messages` 
                                (`chat_id`, `sender`, `text`)
                                VALUES (?, ?, ?)", 
                                [$data['chat_id'], $data['sender'], $data['text']]);
    }

    public function get_from_chat($chat_id)
    {
        return Database::query("SELECT * 
                                FROM messages 
                                WHERE chat_id = ? 
                                ORDER BY date_sent ASC", 
                                [$chat_id])->getResultArray();
    }
}