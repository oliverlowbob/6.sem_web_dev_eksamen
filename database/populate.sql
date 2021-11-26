ALTER TABLE `films`.`cast` 
DROP FOREIGN KEY `fk_cast_movies1`,
DROP FOREIGN KEY `fk_cast_persons1`;
ALTER TABLE `films`.`cast` 
ADD CONSTRAINT `fk_cast_movies1`
  FOREIGN KEY (`movies_id`)
  REFERENCES `films`.`movies` (`id`)
  ON DELETE CASCADE,
ADD CONSTRAINT `fk_cast_persons1`
  FOREIGN KEY (`persons_id`)
  REFERENCES `films`.`persons` (`id`)
  ON DELETE CASCADE;
  
ALTER TABLE `films`.`directors` 
DROP FOREIGN KEY `fk_director_movies1`,
DROP FOREIGN KEY `fk_director_person`;
ALTER TABLE `films`.`directors` 
ADD CONSTRAINT `fk_director_movies1`
  FOREIGN KEY (`movies_id`)
  REFERENCES `films`.`movies` (`id`)
  ON DELETE CASCADE,
ADD CONSTRAINT `fk_director_person`
  FOREIGN KEY (`person_id`)
  REFERENCES `films`.`persons` (`id`)
  ON DELETE CASCADE;


INSERT INTO `films`.`persons` (`name`) VALUES ('Tommy Lee');
INSERT INTO `films`.`persons` (`name`) VALUES ('Dronning Margrethe');
INSERT INTO `films`.`persons` (`name`) VALUES ('Fed Makker');
INSERT INTO `films`.`persons` (`name`) VALUES ('Endnu Federe Makker');

INSERT INTO `films`.`movies` (`title`, `overview`, `released`, `runtime`) VALUES ('Klasse Film', 'Denne film er mega fed', '1995-05-05', '120');

INSERT INTO `films`.`directors` (`person_id`, `movies_id`) VALUES (2, 1);

INSERT INTO `films`.`cast` (`persons_id`, `movies_id`) VALUES (1, 1);
INSERT INTO `films`.`cast` (`persons_id`, `movies_id`) VALUES (3, 1);
INSERT INTO `films`.`cast` (`persons_id`, `movies_id`) VALUES (4, 1);
