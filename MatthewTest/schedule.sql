DROP TABLE IF EXISTS user1.schedule;

CREATE TABLE user1.schedule (
	week				int NOT NULL,
	home				VARCHAR(30) NOT NULL,
	away				VARCHAR(30) NOT NULL,
	winner				VARCHAR(30),
	loser				VARCHAR(30),
	winpoints			int,
	losepoints			int,
	complete			boolean,
	
	FOREIGN KEY (home) REFERENCES user1.user_info(username),
	FOREIGN KEY (away) REFERENCES user1.user_info(username),
	FOREIGN KEY (winner) REFERENCES user1.user_info(username),
	FOREIGN KEY (loser) REFERENCES user1.user_info(username)
);

