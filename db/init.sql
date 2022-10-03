CREATE TABLE `queries` (
  `keywords` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processed` INT(1) DEFAULT 0
);
