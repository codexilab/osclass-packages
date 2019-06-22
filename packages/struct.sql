CREATE TABLE /*TABLE_PREFIX*/t_packages (
	pk_i_id INT NOT NULL AUTO_INCREMENT,
	dt_date DATE NOT NULL,
	dt_update DATE NOT NULL,
	s_name VARCHAR(250) NULL,
	b_company BOOLEAN NOT NULL DEFAULT FALSE,
	i_free_items INT NULL,
	s_pay_frequency ENUM('month', 'quarterly', 'year') NULL,
	b_active BOOLEAN NOT NULL DEFAULT TRUE,
	i_price INT NULL,

	PRIMARY KEY (pk_i_id)
)	ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_packages_assigned (
	pk_i_id INT NOT NULL AUTO_INCREMENT,
	fk_i_user_id INT NULL,
	fk_i_package_id INT NULL,
	dt_date DATETIME NOT NULL,
	dt_from_date DATETIME NOT NULL,
	dt_to_date DATETIME NOT NULL,
	fk_i_invoice_id INT NULL,

	PRIMARY KEY (pk_i_id),
	INDEX (fk_i_package_id),
	FOREIGN KEY (fk_i_package_id) REFERENCES /*TABLE_PREFIX*/t_packages (pk_i_id)
)	ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

CREATE TABLE /*TABLE_PREFIX*/t_packages_items (
	fk_i_item_id INT UNSIGNED NOT NULL,
	fk_i_assignment_id INT NULL,
    dt_date DATETIME NOT NULL,

    PRIMARY KEY (fk_i_item_id)
) 	ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';