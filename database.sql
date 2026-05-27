CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,
  is_admin BOOLEAN DEFAULT FALSE,
  twofa_enabled BOOLEAN DEFAULT FALSE,
  twofa_secret VARCHAR(64),
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE filament_spools (
  id SERIAL PRIMARY KEY,
  brand VARCHAR(100),
  material VARCHAR(50),
  color VARCHAR(50),
  initial_weight_g INT NOT NULL,
  remaining_weight_g INT NOT NULL,
  price_eur NUMERIC(10,2),
  storage_location VARCHAR(100),
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE print_jobs (
  id SERIAL PRIMARY KEY,
  spool_id INT REFERENCES filament_spools(id),
  model_name VARCHAR(150),
  material_used_g INT NOT NULL,
  print_time_minutes INT,
  sale_price_eur NUMERIC(10,2),
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE maintenance_tasks (
  id SERIAL PRIMARY KEY,
  task_name VARCHAR(150) NOT NULL,
  interval_days INT,
  last_done_at DATE,
  notes TEXT
);
