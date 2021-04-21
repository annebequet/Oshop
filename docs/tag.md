# Requêtes pour travailler avec les tags

## Récupérer tous les produits et leurs tags associées

```sql
SELECT
    `product`.`name`,
    `product`.`id`,
    `product_has_tag`.*,
    `tag`.`id`,
    `tag`.`name`
FROM `product`
INNER JOIN `product_has_tag`
    ON `product`.`id`= `product_has_tag`.`product_id`
INNER JOIN `tag`
    ON `product_has_tag`.`tag_id` = `tag`.`id`
ORDER BY
    `product`.`id`
```

## Récupérer tous les tags pour un produit demandé (id produit)
```sql
SELECT
    `tag`.`id`,
    `tag`.`name`,
    `tag`.`created_at`,
    `tag`.`updated_at`
FROM `product`
INNER JOIN `product_has_tag`
    ON `product`.`id`= `product_has_tag`.`product_id`
INNER JOIN `tag`
    ON `product_has_tag`.`tag_id` = `tag`.`id`
WHERE
    `product`.`id` = :id
ORDER BY
    `product`.`id`
```


## BONUS : Récupérer tous les tags pour un produit demandé (id produit)
```sql
SELECT
    `tag`.`id`,
    `tag`.`name`,
    `tag`.`created_at`,
    `tag`.`updated_at`
FROM `product`
INNER JOIN `product_has_tag`
    ON `product`.`id`= `product_has_tag`.`product_id`
    AND `product`.`id`= :id
INNER JOIN `tag`
    ON `product_has_tag`.`tag_id` = `tag`.`id`
ORDER BY
    `product`.`id`
```