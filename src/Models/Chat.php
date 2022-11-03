<?php
namespace SpseiMarketplace\Models;

use SpseiMarketplace\Core\Database;

class Chat extends BaseModel
{
    public function post($data)
    {
        return Database::query("INSERT INTO `chats` 
                                (`chat_id`, `user_id`)
                                VALUES (?, ?)", 
                                [$data['chat_id'], $data['user_id']]);
    }

    public function get_all_chats_with_info_from_user($user_id)
    {
        return Database::query("SELECT c.chat_id AS 'chat_id', u.user_id AS 'user_id', u.first_name AS 'first_name', u.last_name AS 'last_name', u.email AS 'email', m.text AS 'last_text', m.date_sent AS 'date_sent'
                                FROM chats c
                                LEFT JOIN users u ON u.user_id = c.user_id
                                LEFT JOIN (SELECT chat_id, `text`, date_sent FROM messages ORDER BY date_sent DESC LIMIT 1) m ON m.chat_id = c.chat_id
                                WHERE c.chat_id IN (SELECT chat_id FROM chats WHERE user_id = ?) AND c.user_id != ?",
                                [$user_id, $user_id])->getResultArray();
    }

    public function is_chat_mine($chat_id, $user_id)
    {
        $result = Database::query("SELECT chat_id 
                                    FROM chats 
                                    WHERE chat_id = ? AND user_id = ?", 
                                    [$chat_id, $user_id])->getRowArray();
        return $result;
    }

    public function get_chat_id($my_user_id, $target_user_id)
    {
        $result = Database::query("SELECT c.chat_id AS 'chat_id' 
                                    FROM chats c
                                    INNER JOIN (SELECT chat_id FROM chats GROUP BY chat_id HAVING COUNT(*) > 1) o ON o.chat_id = c.chat_id
                                    WHERE user_id IN (?, ?)", [$my_user_id, $target_user_id])->getRowArray();
                                    
        return ($result ? $result['chat_id'] : false);
    }
}