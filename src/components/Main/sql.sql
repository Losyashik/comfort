SELECT
    `orders`.`id` AS `id`,
    order_cites.name AS `city`,
    CONCAT(
        `street`,
        ", ",
        `house`,
        ", ",
        `corpus`,
        ", ",
        `entrance`,
        ", ",
        `flat`
    ) AS `addres`,
    `number`,
    order_status.name AS `status`,
    `note`,
    order_payment_method.short_name AS `short_payment_method`,
    `no_order_1c`,
    `note`
FROM
    orders,
    order_payment_method,
    order_status,
    order_cites
WHERE
    order_payment_method.id = orders.payment_method
    AND order_status.id = id_status
    AND order_cites.id = id_city
    AND id_status IN(1, 2, 3, 4, 5, 6, 11)
ORDER BY
    id DESC