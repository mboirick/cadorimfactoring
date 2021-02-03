DELIMITER  $$

DROP PROCEDURE IF EXISTS searchBalanceClientByDate$$

CREATE PROCEDURE  searchBalanceClientByDate(IN createdDate VARCHAR(255))
BEGIN
SELECT DISTINCT(A.id_client), A.solde_avant_mru, A.solde_mru, U.email, A.created_at
FROM agences as A
left join users as U on A.id_client = U.id_client
WHERE A.created_at like CONCAT('%', createdDate, '%')
GROUP BY  A.id_client
ORDER by A.created_at;

END $$

DELIMITER  $$

DROP PROCEDURE IF EXISTS searchBalanceBusinessByIndex$$

CREATE PROCEDURE  searchBalanceBusinessByIndex(
IN indexValue VARCHAR(255),
IN createdDate VARCHAR(255)
)
BEGIN
	SELECT DISTINCT(s.id_client), u.firstname, s.solde_euros, s.solde_mru, s.created_at, s.solde_avant_euros, s.solde_avant_mru
    FROM solde_client as s
    left join users as u on s.id_client = u.id_client
    WHERE s.indice = indexValue AND u.email_verified_at is not null AND s.created_at like CONCAT('%', createdDate,'%')
    GROUP BY  s.id_client

    UNION

    SELECT DISTINCT(s.id_client), u.firstname, s.solde_euros, s.solde_mru, s.created_at, s.solde_avant_euros, s.solde_avant_mru
    FROM solde_client as s
    left join users as u on s.id_client = u.id_client
    WHERE s.indice = indexValue AND
            u.email_verified_at is not null AND
            s.id_client not IN(
                    SELECT DISTINCT(sc.id_client)
                    FROM solde_client as sc
                    left join users as ur on sc.id_client = ur.id_client
                    WHERE sc.indice = indexValue AND ur.email_verified_at is not null AND sc.created_at like CONCAT('%', createdDate,'%')
                    GROUP BY  sc.id_client
            )
    GROUP BY  s.id_client

    ORDER by firstname, created_at;
END $$

DELIMITER  $$
DROP PROCEDURE IF EXISTS searchByCritere$$
CREATE PROCEDURE  searchByCritere(
IN datestart VARCHAR(255), 
IN dateend   VARCHAR(255), 
IN nombre VARCHAR(255)
)
BEGIN
	SELECT A.id, A.prenom, A.email, A.username, A.pays_residence, A.id_parrain, count(B.id_parrain) AS number, A.confirmed_at
	FROM abonnes as A
	LEFT JOIN abonnes as B on A.id = B.id_parrain 
	WHERE (cast(A.confirmed_at as date) >= datestart OR datestart IS NULL OR datestart = '')
	  AND (cast(A.confirmed_at as date) <= dateend OR dateend IS NULL OR dateend = '')
	GROUP BY A.id
	HAVING (count(B.id_parrain) = nombre OR nombre IS NULL OR nombre = '' )
	ORDER BY number desc;
END $$

CREATE TABLE `log_sendmail` (
  `id` int(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `datesend` date NOT NULL,
  `state` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `task_sendmailparrainage`
--

CREATE TABLE `task_sendmailparrainage` (
  `id` int(11) NOT NULL,
  `sendall` tinyint(1) DEFAULT NULL,
  `datestart` date DEFAULT NULL,
  `dateend` date DEFAULT NULL,
  `numbre` int(255) DEFAULT NULL,
  `dateexcute` date DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `log_sendmail`
--
ALTER TABLE `log_sendmail`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `task_sendmailparrainage`
--
ALTER TABLE `task_sendmailparrainage`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `log_sendmail`
--
ALTER TABLE `log_sendmail`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3957;

--
-- AUTO_INCREMENT pour la table `task_sendmailparrainage`
--
ALTER TABLE `task_sendmailparrainage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;
