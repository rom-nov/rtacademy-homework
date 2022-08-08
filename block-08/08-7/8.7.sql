INSERT INTO category (title, alias)
VALUE ('category1', 'alias_category1'), ('category2', 'alias_category2'),
      ('category3', 'alias_category3'), ('category4', 'alias_category4'),
      ('category5', 'alias_category5');

INSERT INTO user (login, password, email)
VALUE ('user1', 'user1pass', 'user1@gmail.com'), ('user2', 'user2pass', 'user2@gmail.com'),
      ('user3', 'user3pass', 'user3@gmail.com'), ('user4', 'user4pass', 'user4@gmail.com');

UPDATE user
SET lastname = 'user_lastname',
    firstname = 'user_firstname'
WHERE lastname IS NULL AND firstname IS NULL;

UPDATE user
SET lastname = CONCAT(user.lastname, user.id),
    firstname = CONCAT(user.firstname, user.id);

INSERT INTO post (author_id, category_id, title, alias, content, publish_date, status)
VALUE (1, 1, 'title1', 'alias_title1', 'content for post 1', '2022-06-11 13:43:01', 'add'),
      (2, 2, 'title2', 'alias_title2', 'content for post 2', '2022-05-10 12:23:00', 'add'),
      (3, 3, 'title3', 'alias_title3', 'content for post 3', '2022-04-05 21:11:09', 'add'),
      (4, 4, 'title4', 'alias_title4', 'content for post 4', '2022-07-23 07:13:04', 'add'),
      (1, 5, 'title5', 'alias_title5', 'content for post 5', '2022-06-11 13:13:01', 'edit'),
      (2, 1, 'title6', 'alias_title6', 'content for post 6', '2022-05-03 02:22:00', 'edit'),
      (3, 2, 'title7', 'alias_title7', 'content for post 7', '2022-04-07 11:12:09', 'edit'),
      (4, 3, 'title8', 'alias_title8', 'content for post 8', '2022-07-15 17:23:04', 'edit'),
      (1, 4, 'title9', 'alias_title9', 'content for post 9', '2022-03-04 23:01:03', 'add'),
      (2, 5, 'title10', 'alias_title10', 'content for post 10', '2022-04-16 09:03:04', 'add');

INSERT INTO post_comment (user_id, post_id, created_date, comment)
VALUE (4, 1, '2022-03-03 12:12:00', 'comment for post 1'),
      (3, 2, '2022-03-03 12:12:00', 'comment for post 2'),
      (2, 3, '2022-03-03 12:12:00', 'comment for post 3'),
      (1, 4, '2022-03-03 12:12:00', 'comment for post 4'),
      (4, 5, '2022-03-03 12:12:00', 'comment for post 5');

SELECT
    post.id              AS post_id,
    user.login           AS author_name,
    user.email           AS author_email,
    user.lastname        AS author_lastname,
    user.firstname       AS author_firstname,
    category.title       AS category_name,
    post.title           AS title_post,
    post.publish_date    AS post_date,
    post_comment.comment AS post_comment
FROM
    post
        LEFT JOIN
    user ON (post.author_id = user.id)
        LEFT JOIN
    category ON (post.category_id = category.id)
        LEFT JOIN
    post_comment ON (post_comment.post_id = post.id)
WHERE post.status = 'add'
ORDER BY post.id DESC;