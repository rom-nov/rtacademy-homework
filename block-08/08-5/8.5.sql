SELECT
    cities.id       AS city_id,
    cities.name     AS city_name,
    countries.name  AS countries_name,
    continents.name AS contunent_name
FROM
    cities
LEFT JOIN
    countries ON ( cities.country_id = countries.id )
LEFT JOIN
    continents ON ( cities.continent_id = continents.id )
ORDER BY
    LENGTH( cities.name ) DESC
LIMIT
    10;
