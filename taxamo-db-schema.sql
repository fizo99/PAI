/*
 Navicat Premium Data Transfer

 Source Server         : taxamo
 Source Server Type    : PostgreSQL
 Source Server Version : 130005
 Source Host           : ec2-54-155-35-88.eu-west-1.compute.amazonaws.com:5432
 Source Catalog        : d875r5nlk57u7t
 Source Schema         : public

 Target Server Type    : PostgreSQL
 Target Server Version : 130005
 File Encoding         : 65001

 Date: 02/02/2022 17:35:06
*/


-- ----------------------------
-- Sequence structure for adress_incre_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."adress_incre_seq";
CREATE SEQUENCE "public"."adress_incre_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for invoice_incre_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."invoice_incre_seq";
CREATE SEQUENCE "public"."invoice_incre_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1
CYCLE ;

-- ----------------------------
-- Sequence structure for invoice_state_incre_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."invoice_state_incre_seq";
CREATE SEQUENCE "public"."invoice_state_incre_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for invoices_types_incre_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."invoices_types_incre_seq";
CREATE SEQUENCE "public"."invoices_types_incre_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for paymentmethod_incre_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."paymentmethod_incre_seq";
CREATE SEQUENCE "public"."paymentmethod_incre_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for product_incre_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."product_incre_seq";
CREATE SEQUENCE "public"."product_incre_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for user_incre_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."user_incre_seq";
CREATE SEQUENCE "public"."user_incre_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Table structure for addresses
-- ----------------------------
DROP TABLE IF EXISTS "public"."addresses";
CREATE TABLE "public"."addresses" (
  "address_id" int4 NOT NULL DEFAULT nextval('adress_incre_seq'::regclass),
  "city" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "zip_code" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "street_name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "street_nr" varchar(4) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Table structure for companies
-- ----------------------------
DROP TABLE IF EXISTS "public"."companies";
CREATE TABLE "public"."companies" (
  "nip" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "name" varchar(100) COLLATE "pg_catalog"."default",
  "email" varchar(100) COLLATE "pg_catalog"."default",
  "phone_number" varchar(10) COLLATE "pg_catalog"."default",
  "iban" varchar(70) COLLATE "pg_catalog"."default",
  "address_id" int4
)
;

-- ----------------------------
-- Table structure for invoices
-- ----------------------------
DROP TABLE IF EXISTS "public"."invoices";
CREATE TABLE "public"."invoices" (
  "invoice_id" int4 NOT NULL DEFAULT nextval('invoice_incre_seq'::regclass),
  "buyer_id" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "seller_id" varchar(10) COLLATE "pg_catalog"."default" NOT NULL,
  "place" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "date" date NOT NULL,
  "number" int4 NOT NULL,
  "payment_method_id" int4 NOT NULL,
  "additional_info" varchar(1024) COLLATE "pg_catalog"."default",
  "user_id" int4 NOT NULL,
  "invoice_type_id" int4 NOT NULL,
  "invoice_state_id" int4 NOT NULL
)
;

-- ----------------------------
-- Table structure for invoices_products
-- ----------------------------
DROP TABLE IF EXISTS "public"."invoices_products";
CREATE TABLE "public"."invoices_products" (
  "invoice_id" int4 NOT NULL,
  "product_id" int4 NOT NULL,
  "quantity" int4 NOT NULL
)
;

-- ----------------------------
-- Table structure for invoices_states
-- ----------------------------
DROP TABLE IF EXISTS "public"."invoices_states";
CREATE TABLE "public"."invoices_states" (
  "invoice_state_id" int4 NOT NULL DEFAULT nextval('invoice_state_incre_seq'::regclass),
  "invoice_state" varchar(255) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Table structure for invoices_types
-- ----------------------------
DROP TABLE IF EXISTS "public"."invoices_types";
CREATE TABLE "public"."invoices_types" (
  "invoice_type_id" int4 NOT NULL DEFAULT nextval('invoices_types_incre_seq'::regclass),
  "invoice_type" varchar(255) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Table structure for payment_methods
-- ----------------------------
DROP TABLE IF EXISTS "public"."payment_methods";
CREATE TABLE "public"."payment_methods" (
  "payment_method_id" int2 NOT NULL DEFAULT nextval('paymentmethod_incre_seq'::regclass),
  "payment_method" varchar(100) COLLATE "pg_catalog"."default" NOT NULL
)
;

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS "public"."products";
CREATE TABLE "public"."products" (
  "product_id" int4 NOT NULL DEFAULT nextval('product_incre_seq'::regclass),
  "name" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "unit" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "netto_price" numeric(10,2) NOT NULL,
  "tax_percent" int2 NOT NULL
)
;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS "public"."users";
CREATE TABLE "public"."users" (
  "user_id" int4 NOT NULL DEFAULT nextval('user_incre_seq'::regclass),
  "email" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "password" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "company_id" varchar(10) COLLATE "pg_catalog"."default",
  "is_demo" bool NOT NULL
)
;

-- ----------------------------
-- Function structure for invoices_basic_data
-- ----------------------------
DROP FUNCTION IF EXISTS "public"."invoices_basic_data"("user_id" int4);
CREATE OR REPLACE FUNCTION "public"."invoices_basic_data"("user_id" int4)
  RETURNS TABLE("invoice_id" text, "nip" text, "number" varchar, "date" date, "buyer_name" varchar, "state" varchar, "total_brutto_value" numeric) AS $BODY$
	
-- 	 SELECT invoices_products.invoice_id,
--     invoices.date,
--     companies.name AS buyer_name,
--     round(sum(products.netto_price * (products.tax_percent::numeric / 100::numeric) * invoices_products.quantity::numeric), 2) AS total_brutto_value
--    FROM invoices_products
--      JOIN products ON products.product_id = invoices_products.product_id
--      JOIN invoices ON invoices.invoice_id = invoices_products.invoice_id
--      JOIN companies ON companies.nip::text = invoices.buyer_id::text
--  WHERE invoices.user_id = $1

	 SELECT invoices.invoice_id,
	 invoices.buyer_id as nip,
	 invoices.number,
	 invoices.date,
	 companies.name,
	 invoices_states.invoice_state,
	 invoices_total_brutto_value(invoices.invoice_id)
	 FROM invoices
		JOIN companies on invoices.buyer_id = companies.nip
		JOIN invoices_states on invoices.invoice_state_id = invoices_states.invoice_state_id
	 where invoices.user_id = $1;
--     invoices.date,
--     companies.name AS buyer_name,
--     round(sum(products.netto_price * (products.tax_percent::numeric / 100::numeric) * invoices_products.quantity::numeric), 2) AS total_brutto_value
--    FROM invoices_products
--      JOIN products ON products.product_id = invoices_products.product_id
--      JOIN invoices ON invoices.invoice_id = invoices_products.invoice_id
--      JOIN companies ON companies.nip::text = invoices.buyer_id::text
--  WHERE invoices.user_id = $1
 
 $BODY$
  LANGUAGE sql VOLATILE
  COST 100
  ROWS 1000;

-- ----------------------------
-- Function structure for invoices_total_brutto_value
-- ----------------------------
DROP FUNCTION IF EXISTS "public"."invoices_total_brutto_value"("invoice_id" int4);
CREATE OR REPLACE FUNCTION "public"."invoices_total_brutto_value"("invoice_id" int4)
  RETURNS TABLE("total_brutto_value" numeric) AS $BODY$
-- 	 SELECT round(sum(products.netto_price * (products.tax_percent::numeric / 100::numeric) * invoices_products.quantity::numeric), 2) AS total_brutto_value
-- 	 FROM invoices_products
-- 		JOIN products on products.product_id = invoices_products.product_id
-- 		WHERE invoices_products.invoice_id = $1

	WITH brutto_prices (product_id, brutto) as
			 (select products.product_id, (netto_price + netto_price * (tax_percent::numeric / 100::numeric)) * quantity as brutto 
			 from invoices_products 
				join products on products.product_id = invoices_products.product_id
				where invoices_products.invoice_id = $1
-- 			 group by invoices_products.invoice_id
-- 			 having invoices_products.invoice_id = $1
			 )
	 SELECT 
		round(sum(brutto)::numeric,2)
	 from brutto_prices

 $BODY$
  LANGUAGE sql VOLATILE
  COST 100
  ROWS 1000;

-- ----------------------------
-- View structure for total_brutto_value
-- ----------------------------
DROP VIEW IF EXISTS "public"."total_brutto_value";
CREATE VIEW "public"."total_brutto_value" AS  SELECT invoices_products.invoice_id,
    invoices.date,
    companies.name AS buyer_name,
    round(sum(products.netto_price * (products.tax_percent::numeric / 100::numeric) * invoices_products.quantity::numeric), 2) AS total_brutto_value
   FROM invoices_products
     JOIN products ON products.product_id = invoices_products.product_id
     JOIN invoices ON invoices.invoice_id = invoices_products.invoice_id
     JOIN companies ON companies.nip::text = invoices.buyer_id::text
  GROUP BY invoices_products.invoice_id, companies.name, invoices.date
 HAVING invoices_products.invoice_id = 1;

-- ----------------------------
-- View structure for invoices_total_values
-- ----------------------------
DROP VIEW IF EXISTS "public"."invoices_total_values";
CREATE VIEW "public"."invoices_total_values" AS  SELECT invoices_products.invoice_id,
    sum(products.netto_price * invoices_products.quantity::numeric) AS total_value
   FROM invoices_products
     JOIN products ON products.product_id = invoices_products.product_id
  GROUP BY invoices_products.invoice_id;

-- ----------------------------
-- View structure for invoice_items_with_summed_value
-- ----------------------------
DROP VIEW IF EXISTS "public"."invoice_items_with_summed_value";
CREATE VIEW "public"."invoice_items_with_summed_value" AS  SELECT invoices_products.invoice_id,
    products.name AS product,
    invoices_products.quantity,
    products.netto_price AS unit_price,
    products.netto_price * invoices_products.quantity::numeric AS total_value
   FROM invoices_products
     JOIN products ON products.product_id = invoices_products.product_id;

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."adress_incre_seq"
OWNED BY "public"."addresses"."address_id";
SELECT setval('"public"."adress_incre_seq"', 80, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."invoice_incre_seq"
OWNED BY "public"."invoices"."invoice_id";
SELECT setval('"public"."invoice_incre_seq"', 32, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."invoice_state_incre_seq"
OWNED BY "public"."invoices_states"."invoice_state_id";
SELECT setval('"public"."invoice_state_incre_seq"', 4, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."invoices_types_incre_seq"
OWNED BY "public"."invoices_types"."invoice_type_id";
SELECT setval('"public"."invoices_types_incre_seq"', 3, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."paymentmethod_incre_seq"
OWNED BY "public"."payment_methods"."payment_method_id";
SELECT setval('"public"."paymentmethod_incre_seq"', 2, false);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."product_incre_seq"
OWNED BY "public"."products"."product_id";
SELECT setval('"public"."product_incre_seq"', 58, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."user_incre_seq"
OWNED BY "public"."users"."user_id";
SELECT setval('"public"."user_incre_seq"', 28, true);

-- ----------------------------
-- Primary Key structure for table addresses
-- ----------------------------
ALTER TABLE "public"."addresses" ADD CONSTRAINT "addresses_pkey" PRIMARY KEY ("address_id");

-- ----------------------------
-- Primary Key structure for table companies
-- ----------------------------
ALTER TABLE "public"."companies" ADD CONSTRAINT "persons_pkey" PRIMARY KEY ("nip");

-- ----------------------------
-- Primary Key structure for table invoices
-- ----------------------------
ALTER TABLE "public"."invoices" ADD CONSTRAINT "invoices_pkey" PRIMARY KEY ("invoice_id");

-- ----------------------------
-- Primary Key structure for table invoices_products
-- ----------------------------
ALTER TABLE "public"."invoices_products" ADD CONSTRAINT "invoices_products_pkey" PRIMARY KEY ("invoice_id", "product_id");

-- ----------------------------
-- Primary Key structure for table invoices_states
-- ----------------------------
ALTER TABLE "public"."invoices_states" ADD CONSTRAINT "invoices_states_pkey" PRIMARY KEY ("invoice_state_id");

-- ----------------------------
-- Primary Key structure for table invoices_types
-- ----------------------------
ALTER TABLE "public"."invoices_types" ADD CONSTRAINT "invoices_types_pkey" PRIMARY KEY ("invoice_type_id");

-- ----------------------------
-- Primary Key structure for table payment_methods
-- ----------------------------
ALTER TABLE "public"."payment_methods" ADD CONSTRAINT "paymentMethods_pkey" PRIMARY KEY ("payment_method_id");

-- ----------------------------
-- Primary Key structure for table products
-- ----------------------------
ALTER TABLE "public"."products" ADD CONSTRAINT "product_pkey" PRIMARY KEY ("product_id");

-- ----------------------------
-- Uniques structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD CONSTRAINT "unique_email" UNIQUE ("email");

-- ----------------------------
-- Primary Key structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD CONSTRAINT "users_pkey" PRIMARY KEY ("user_id");

-- ----------------------------
-- Foreign Keys structure for table companies
-- ----------------------------
ALTER TABLE "public"."companies" ADD CONSTRAINT "fk_address_id" FOREIGN KEY ("address_id") REFERENCES "public"."addresses" ("address_id") ON DELETE RESTRICT ON UPDATE RESTRICT;

-- ----------------------------
-- Foreign Keys structure for table invoices
-- ----------------------------
ALTER TABLE "public"."invoices" ADD CONSTRAINT "fk_buyer_id" FOREIGN KEY ("buyer_id") REFERENCES "public"."companies" ("nip") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."invoices" ADD CONSTRAINT "fk_invoice_state_id" FOREIGN KEY ("invoice_state_id") REFERENCES "public"."invoices_states" ("invoice_state_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."invoices" ADD CONSTRAINT "fk_invoice_type_id" FOREIGN KEY ("invoice_type_id") REFERENCES "public"."invoices_types" ("invoice_type_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."invoices" ADD CONSTRAINT "fk_payment_method" FOREIGN KEY ("payment_method_id") REFERENCES "public"."payment_methods" ("payment_method_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."invoices" ADD CONSTRAINT "fk_seller_id" FOREIGN KEY ("seller_id") REFERENCES "public"."companies" ("nip") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "public"."invoices" ADD CONSTRAINT "fk_user_id" FOREIGN KEY ("user_id") REFERENCES "public"."users" ("user_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table invoices_products
-- ----------------------------
ALTER TABLE "public"."invoices_products" ADD CONSTRAINT "fk_invoice_id" FOREIGN KEY ("invoice_id") REFERENCES "public"."invoices" ("invoice_id") ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE "public"."invoices_products" ADD CONSTRAINT "fk_product_id" FOREIGN KEY ("product_id") REFERENCES "public"."products" ("product_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD CONSTRAINT "fk_company_id" FOREIGN KEY ("company_id") REFERENCES "public"."companies" ("nip") ON DELETE NO ACTION ON UPDATE NO ACTION;
