--
-- PostgreSQL database dump
--

-- Dumped from database version 15.7 (Debian 15.7-1.pgdg120+1)
-- Dumped by pg_dump version 15.8 (Ubuntu 15.8-1.pgdg22.04+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.users (id, name, email, password, type, active, created_at, updated_at) FROM stdin;
1	admin	adm@mail.com	$2y$12$aOMbE74ykWOPtQiM3T2tIuoGLzhcw8KN7vWYVlCUjd3uvQrE46spW	admin	t	2024-10-11 01:25:10	2024-10-11 01:25:10
2	admin	jorge@mail.com	$2y$12$0ZlueLilfNzJshpgj9eQN.QD1VudeRdIV9IKkEqVQrNayFQRgEEoG	admin	t	2024-10-14 03:57:49	2024-10-14 03:57:49
3	admin	jorge@mail0.com	$2y$12$k6QYtqzJj.8uFTP0lMpBnOOIAZ5Ze6CksAAlLx68hd.7QZDu0050m	admin	t	2024-10-14 03:58:12	2024-10-14 03:58:12
4	Erico	erico@mail.com	$2y$12$OLOZFGjFnnu8Isj4wTkoDuvRcOglJ4S6mwmraPNeZbXOpCDkxp.gm	common	t	2024-12-22 05:46:55	2024-12-22 05:46:55
\.


--
-- Data for Name: clients; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.clients (id, name, "CNPJ", "CEP", address, cellphone, created_at, updated_at, created_by, updated_by) FROM stdin;
2	O'Reilly and Sons	i1713867700032	23080060	1275 Lakin Curve809 S Center Street	17296302011	2024-10-22 17:50:01	2024-10-22 17:50:01	1	1
1	Mario Dedidni	11111111111111	99999994	Rua dos Bobos n0aaaaaaaaaaaaaa	99999999999	2024-10-14 03:52:11	2024-12-22 05:41:46	1	1
3	Juca das Silvas	88888888888888	85909531	Perto de lugar nenhum e longe daqui	66666666666	2024-12-11 06:11:34	2024-12-22 05:42:27	1	1
4	Joãozinho	44444444444444	55555555	Rua Altarquia 6666666666	88195111111	2024-12-22 05:48:08	2024-12-22 05:48:08	1	1
\.


--
-- Data for Name: budgets; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.budgets (id, client, delivery_date, delivery_address, provider_name, provider_city, unloaded, payment_status, payment_method, payment_date, revenue, cost, created_at, updated_at, created_by, updated_by) FROM stdin;
1	2	2024-08-25	Casa da mãe Joana	Jeanne Alter	Saltinfho	client	paid	pix	1997-07-12	200	50.33	2024-11-15 09:28:08	2024-11-15 09:28:08	1	1
2	1	2024-12-23	Rua Amélida	Juca	Limeira	carrier	pending	ticket	2024-12-24	6000	1000	2024-12-22 05:40:18	2024-12-22 05:41:05	1	1
2000	1	2024-12-23	Rua Amélida	Juca	Limeira	carrier	pending	ticket	2024-12-24	6000	1000	2024-12-22 05:40:18	2024-12-22 05:40:18	1	1
3	2	2024-08-25	Casa da mãe Joana	Jeanne Alter	Saltinfho	client	paid	pix	1997-07-12	200	50.33	2025-01-22 07:55:20	2025-01-22 07:55:20	1	1
\.


--
-- Data for Name: stock_type; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.stock_type (id, name, created_at, updated_at) FROM stdin;
1	Paletes	2024-10-14 04:09:06	2024-10-14 04:09:06
2	Metragem	2024-10-14 04:09:10	2024-10-14 04:09:10
3	Sacos	2024-10-14 04:09:13	2024-10-14 04:09:13
\.


--
-- Data for Name: stocks; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.stocks (id, name, weight, quantity, extra, created_at, updated_at, type) FROM stdin;
1	Argamassa	266	100	\N	2024-10-22 18:04:21	2024-10-22 18:04:21	1
2	Cimento	266	100	ok	2024-10-22 18:04:21	2024-10-22 18:04:21	2
3	Argamassa	266	100	\N	2024-10-22 18:04:55	2024-10-22 18:04:55	1
4	Cimento	266	100	ok	2024-10-22 18:04:55	2024-10-22 18:04:55	2
17	Argamassa	266	100	\N	2024-11-15 09:19:48	2024-11-15 09:19:48	1
18	Cimento	266	100	ok	2024-11-15 09:19:48	2024-11-15 09:19:48	2
19	Argamassa	266	100	\N	2024-11-15 09:28:08	2024-11-15 09:28:08	1
20	Cimento	266	100	ok	2024-11-15 09:28:08	2024-11-15 09:28:08	2
21	Argamassa	6	10	\N	2024-11-15 10:03:22	2024-11-15 10:03:22	1
22	Cimento	26	10	ok	2024-11-15 10:03:22	2024-11-15 10:03:22	2
23	Argamassa	266	100	\N	2024-11-15 10:05:41	2024-11-15 10:05:41	1
24	Cimento	266	100	ok	2024-11-15 10:05:41	2024-11-15 10:05:41	2
25	Argamassa	266	100	\N	2024-12-01 09:27:41	2024-12-01 09:27:41	1
26	Cimento	391	50	ok	2024-12-01 09:27:41	2024-12-01 09:27:41	2
29	Argamassa	26	10	\N	2024-12-01 09:30:47	2024-12-01 09:30:47	1
30	Cimento	26	10	ok	2024-12-01 09:30:47	2024-12-01 09:30:47	2
33	Argamassa	266	100	\N	2024-12-01 09:46:43	2024-12-01 09:46:43	1
34	Cimento	266	100	ok	2024-12-01 09:46:43	2024-12-01 09:46:43	2
35	Argamassa	266	100	\N	2024-12-01 09:47:26	2024-12-01 09:47:26	1
36	Cimento	266	100	ok	2024-12-01 09:47:26	2024-12-01 09:47:26	2
37	Argamassa	266	100	\N	2024-12-01 09:49:26	2024-12-01 09:49:26	1
38	Cimento	391	50	ok	2024-12-01 09:49:26	2024-12-01 09:49:26	2
39	Argamassa	266	100	\N	2024-12-01 09:49:30	2024-12-01 09:49:30	1
40	Cimento	391	50	ok	2024-12-01 09:49:30	2024-12-01 09:49:30	2
41	Argamassa	26	10	\N	2024-12-01 09:49:41	2024-12-01 09:49:41	1
42	Cimento	26	10	ok	2024-12-01 09:49:41	2024-12-01 09:49:41	2
43	Argamassa	26	10	\N	2024-12-01 10:11:51	2024-12-01 10:11:51	1
44	Argamasdsa	26	10	\N	2024-12-01 10:13:16	2024-12-01 10:13:16	1
45	Argamassa	26	10	\N	2024-12-01 10:32:58	2024-12-01 10:32:58	1
46	Argamassa	266	100	\N	2024-12-01 11:04:49	2024-12-01 11:04:49	1
47	Cimento	391	50	ok	2024-12-01 11:04:49	2024-12-01 11:04:49	2
50	Madeira	34	66	1|2	2024-12-05 20:46:48	2024-12-05 20:46:48	2
51	Argamassa	26	10	\N	2024-12-08 00:29:06	2024-12-08 00:29:06	1
52	Argamassa	26	50	\N	2024-12-08 00:29:27	2024-12-08 00:29:27	1
53	Argamassa	26	10	\N	2024-12-08 00:31:55	2024-12-08 00:31:55	1
54	Argamassa	26	10	\N	2024-12-08 00:31:57	2024-12-08 00:31:57	1
55	Argamassa	26	10	\N	2024-12-08 00:31:57	2024-12-08 00:31:57	1
59	Cimento	391	25	ok	2024-12-17 21:43:52	2024-12-17 21:43:52	2
60	Argamassa	266	100	\N	2024-12-22 05:30:26	2024-12-22 05:30:26	1
61	Cimento	391	50	ok	2024-12-22 05:30:26	2024-12-22 05:30:26	2
62	Livro	3232	123	\N	2024-12-22 05:38:56	2024-12-22 05:38:56	1
64	Júnio	66	3	\N	2024-12-22 05:41:05	2024-12-22 05:41:05	1
66	Argamassa	20	20	\N	2024-12-24 07:50:33	2024-12-24 07:50:33	1
65	Cimento	10	25	ok	2024-12-22 05:58:49	2024-12-22 05:58:49	2
67	Argamassa	10	13	\N	2024-12-24 07:57:34	2024-12-24 07:57:34	1
68	Cimento	10	11	ok	2024-12-24 08:04:07	2024-12-24 08:04:07	2
69	Mario	30	60	\N	2024-12-24 08:10:04	2024-12-24 08:10:04	3
70	Mario	5	10	\N	2025-01-18 21:56:09	2025-01-18 21:56:09	3
71	Argamassa	266	100	\N	2025-01-22 07:55:20	2025-01-22 07:55:20	1
72	Cimento	266	100	ok	2025-01-22 07:55:20	2025-01-22 07:55:20	2
\.


--
-- Data for Name: budgets_stocks; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.budgets_stocks (id, budget, stock, created_at, updated_at) FROM stdin;
1	1	19	2024-11-15 09:28:08	2024-11-15 09:28:08
2	1	20	2024-11-15 09:28:08	2024-11-15 09:28:08
4	2	64	2024-12-22 05:41:05	2024-12-22 05:41:05
5	3	71	2025-01-22 07:55:20	2025-01-22 07:55:20
6	3	72	2025-01-22 07:55:20	2025-01-22 07:55:20
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: deliveries; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.deliveries (id, client, delivery_date, receipt_date, driver, invoice, delivery_address, provider_name, provider_city, unloaded, payment_status, payment_method, payment_date, revenue, travel_cost, unloading_cost, finished, ref, created_at, updated_at, created_by, updated_by) FROM stdin;
31	2	2024-12-25	\N	Dan	\N	Casa da mãe Joana	\N	\N	client	\N	\N	\N	\N	\N	50.33	t	19	2024-12-08 00:31:57	2024-12-24 07:34:34	1	1
22	2	2024-12-25	\N	Nickolas	\N	Casa da mãe Joana	\N	\N	client	\N	\N	\N	\N	\N	50.33	t	19	2024-12-01 10:13:16	2024-12-24 07:35:44	1	1
33	2	2024-12-18	\N	Maria	\N	Rua dos Bobos	\N	\N	client	\N	\N	\N	\N	\N	0	t	19	2024-12-17 21:43:52	2024-12-24 07:37:14	1	1
28	2	2024-12-25	\N	Coby	\N	Casa da mãe Joana	\N	\N	client	\N	\N	\N	\N	\N	50.33	t	19	2024-12-08 00:29:27	2024-12-24 07:46:43	1	1
19	2	2024-12-16	1997-09-20	Vergie	\N	Casa da mãe Joana	Jeanne Alter	Saltinfho	client	paid	pix	1997-07-12	200	100.33	0	t	\N	2024-12-01 09:49:30	2024-12-24 07:49:33	1	1
20	2	2024-12-25	\N	Karelle	\N	Casa da mãe Joana	\N	\N	client	\N	\N	\N	\N	\N	50.33	t	18	2024-12-01 09:49:41	2024-12-01 11:04:22	1	1
24	2	2024-12-25	1997-09-20	Sam	\N	Casa da mãe Joana	Jeanne Alter	Saltinfho	client	paid	pix	1997-07-12	200	100.33	50.33	t	\N	2024-12-01 11:04:49	2024-12-01 11:04:55	1	1
18	2	2024-12-25	1997-09-20	Clarissa	\N	Casa da mãe Joana	Jeanne Alter	Saltinfho	client	paid	pix	1997-07-12	200	100.33	50.33	t	\N	2024-12-01 09:49:26	2024-12-01 11:05:36	1	1
21	2	2024-12-25	\N	Monique	\N	Casa da mãe Joana	\N	\N	client	\N	\N	\N	\N	\N	50.33	t	18	2024-12-01 10:11:51	2024-12-01 11:05:36	1	1
35	2	2024-12-25	\N	Mario	\N	Rua Lisboa	\N	\N	client	\N	\N	\N	\N	\N	0	t	32	2024-12-24 07:50:33	2024-12-24 07:52:16	1	1
29	2	2024-12-25	\N	Reynold	\N	Casa da mãe Joana	\N	\N	client	\N	\N	\N	\N	\N	50.33	t	19	2024-12-08 00:31:55	2024-12-08 00:34:44	1	1
30	2	2024-12-25	\N	Simeon	\N	Casa da mãe Joana	\N	\N	client	\N	\N	\N	\N	\N	50.33	t	19	2024-12-08 00:31:57	2024-12-08 00:34:48	1	1
26	1	2024-12-09	2024-12-03	sss	\N	Rua dos Bobos	Jorge	Piraci	client	pending	ticket	2024-12-04	200	6	0	t	\N	2024-12-05 20:46:24	2024-12-11 06:37:37	1	1
36	2	2024-12-25	\N	Chumaquer	\N	Rua Amélia	\N	\N	client	\N	\N	\N	\N	\N	0	t	32	2024-12-24 07:57:34	2024-12-24 07:58:14	1	1
23	2	2024-12-25	\N	Gonzalo	\N	Casa da mãe Joana	\N	\N	client	\N	\N	\N	\N	\N	50.33	t	19	2024-12-01 10:32:58	2024-12-22 04:20:54	1	1
32	2	2024-12-25	1997-09-20	Julio	\N	Casa da mãe Joana	Jeanne Alter	Saltinfho	client	paid	pix	1997-07-12	200	100.33	0	f	\N	2024-12-17 06:15:22	2024-12-22 05:30:26	1	1
25	1	2024-12-02	2024-12-09	DASDASDA	77777777777777777777777777777777777777777777	rUDA	rOBERTO	Rua lua	carrier	pending	ticket	2024-12-02	2000	600	0	f	\N	2024-12-03 21:06:17	2024-12-22 05:38:56	1	1
34	2	2024-12-23	\N	Jorge	\N	Rua Amélinda	\N	\N	client	\N	\N	\N	\N	\N	0	t	32	2024-12-22 05:58:49	2024-12-24 08:03:15	1	1
37	2	2024-12-25	\N	Kauna	\N	Rua Amélita	\N	\N	client	\N	\N	\N	\N	\N	0	t	32	2024-12-24 08:04:07	2024-12-24 08:04:24	1	1
38	2	2024-12-31	2024-12-23	Jorge	\N	Rua da Lua	Tenda	Limeira	client	paid	pix	2024-12-25	100	20	0	f	\N	2024-12-24 08:10:04	2024-12-24 08:10:04	1	1
39	2	2025-01-21	\N	Jorge	\N	Rua Amélia	\N	\N	client	\N	\N	\N	\N	\N	0	f	38	2025-01-18 21:56:09	2025-01-18 21:56:09	1	1
27	2	2024-12-25	\N	Julien	\N	Casa da mãe Joana	\N	\N	client	\N	\N	\N	\N	\N	50.33	t	19	2024-12-08 00:29:06	2024-12-22 05:56:31	1	1
\.


--
-- Data for Name: deliveries_stocks; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.deliveries_stocks (id, delivery, stock, created_at, updated_at) FROM stdin;
21	18	37	2024-12-01 09:49:26	2024-12-01 09:49:26
22	18	38	2024-12-01 09:49:26	2024-12-01 09:49:26
23	19	39	2024-12-01 09:49:30	2024-12-01 09:49:30
24	19	40	2024-12-01 09:49:30	2024-12-01 09:49:30
25	20	41	2024-12-01 09:49:41	2024-12-01 09:49:41
26	20	42	2024-12-01 09:49:41	2024-12-01 09:49:41
27	21	43	2024-12-01 10:11:51	2024-12-01 10:11:51
28	22	44	2024-12-01 10:13:16	2024-12-01 10:13:16
29	23	45	2024-12-01 10:32:58	2024-12-01 10:32:58
30	24	46	2024-12-01 11:04:49	2024-12-01 11:04:49
31	24	47	2024-12-01 11:04:49	2024-12-01 11:04:49
34	26	50	2024-12-05 20:46:48	2024-12-05 20:46:48
35	27	51	2024-12-08 00:29:06	2024-12-08 00:29:06
36	28	52	2024-12-08 00:29:27	2024-12-08 00:29:27
37	29	53	2024-12-08 00:31:55	2024-12-08 00:31:55
38	30	54	2024-12-08 00:31:57	2024-12-08 00:31:57
39	31	55	2024-12-08 00:31:57	2024-12-08 00:31:57
43	33	59	2024-12-17 21:43:52	2024-12-17 21:43:52
44	32	60	2024-12-22 05:30:26	2024-12-22 05:30:26
45	32	61	2024-12-22 05:30:26	2024-12-22 05:30:26
46	25	62	2024-12-22 05:38:56	2024-12-22 05:38:56
47	34	65	2024-12-22 05:58:49	2024-12-22 05:58:49
48	35	66	2024-12-24 07:50:33	2024-12-24 07:50:33
49	36	67	2024-12-24 07:57:34	2024-12-24 07:57:34
50	37	68	2024-12-24 08:04:07	2024-12-24 08:04:07
51	38	69	2024-12-24 08:10:04	2024-12-24 08:10:04
52	39	70	2025-01-18 21:56:09	2025-01-18 21:56:09
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2024_07_03_010649_create_sessions_table	1
2	2024_07_03_010748_users	1
3	2024_07_07_015108_create_cache_table	1
4	2024_07_29_004709_clients	1
5	2024_08_25_111257_stock_type	1
6	2024_08_25_122633_stocks	1
34	2024_08_25_123932_budgets	2
35	2024_08_25_222941_budgets_stocks	2
36	2024_09_29_203159_deliveries	3
37	2024_09_29_204613_deliveries_stocks	3
40	2025_01_19_123041_trucks	4
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
ykXMjIoP7DRLKLkoIIuOny5dczzsefZwJ0IgBxR3	\N	172.19.0.1	Mozilla/5.0 (X11; Linux x86_64; rv:131.0) Gecko/20100101 Firefox/131.0	ZXlKcGRpSTZJbFY2WkVGeVRtVmFOMkp6U1hWVU9XZDBUWFl6T0djOVBTSXNJblpoYkhWbElqb2lTakpJTkVnMmEwVkJWbXR0Wm5SbGRUVjNRMWhyZWxsaVpERTBWblpZTDJaelJuaDNkemhFZEVOMFQyVjFSVXhJWVhvMk1WWjFNMVJ3YmpCa2FHbHViV1JuVDA0NWJuSXJOMjF3TVcxSE5XaEJUVUZOYkVJMWMxUjJjVTgxZEZOUk5VVXJURGxUWldZMGR6TldiVUkwYmtvMVVuSkdXbGd2VDNsWk4xTXpiMDl5ZGtsQ2MybEdXV3RaWVdSVllUWnphRFJ1Ylc1NFV5OWtaVkJ2U1M4M2Rta3pSVkYyS3k5blp6aEdWR05qVDFscmNrTXhhSFF3T0ZKcFQwcFpibTU2VkdONVFXUllTa0V3VW1oTk9GcDNSRGxNWTFwV1kzVkdMMUJNZVhWT2MySmFRM0ZHVTFac1RFeGxUVk5hTm1KTlNUVldRVFZzY0ZkV1FrUnJVMFYyY1NJc0ltMWhZeUk2SWpObU4yRXhNRGd4WkRjM01ESXpZVEJoTjJZM056WXdObU01Tm1NME1HRmlaakJsTWpsa1pEaGlOakpqTmpneE5qQmhZakE1TkRrMk1HUXpNemd5WW1RaUxDSjBZV2NpT2lJaWZRPT0=	1728888458
\.


--
-- Data for Name: trucks; Type: TABLE DATA; Schema: public; Owner: sail
--

COPY public.trucks (id, plate, photo, axis, created_at, updated_at, created_by, updated_by) FROM stdin;
1	ABC1234	\N	3	2025-01-19 13:19:12	2025-01-19 13:19:34	1	1
2	CASGHSA	\N	4	2025-01-21 20:07:30	2025-01-21 20:07:30	1	1
3	CAFGHSA	\N	4	2025-01-21 20:10:55	2025-01-21 20:10:55	1	1
5	CAFGGSA	https://res.cloudinary.com/dchtzhdc1/image/upload/v1737504476/lmdoczfqkqxmuhec7mhs.png	4	2025-01-21 21:07:56	2025-01-21 21:07:56	1	1
\.


--
-- Name: budgets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.budgets_id_seq', 3, true);


--
-- Name: budgets_stocks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.budgets_stocks_id_seq', 6, true);


--
-- Name: clients_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.clients_id_seq', 4, true);


--
-- Name: deliveries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.deliveries_id_seq', 39, true);


--
-- Name: deliveries_stocks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.deliveries_stocks_id_seq', 52, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.migrations_id_seq', 40, true);


--
-- Name: stock_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.stock_type_id_seq', 3, true);


--
-- Name: stocks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.stocks_id_seq', 72, true);


--
-- Name: trucks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.trucks_id_seq', 5, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sail
--

SELECT pg_catalog.setval('public.users_id_seq', 4, true);


--
-- PostgreSQL database dump complete
--

