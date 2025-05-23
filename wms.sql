USE [master]
GO
/****** Object:  Database [PSI_WMS]    Script Date: 2025-04-28 7:54:02 AM ******/
CREATE DATABASE [PSI_WMS]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'PSI_WMS', FILENAME = N'D:\WMS_DB\PSI_WMS.mdf' , SIZE = 106854400KB , MAXSIZE = UNLIMITED, FILEGROWTH = 1024KB )
 LOG ON 
( NAME = N'PSI_WMS_log', FILENAME = N'D:\WMS_DB\PSI_WMS_log.ldf' , SIZE = 1536KB , MAXSIZE = 2048GB , FILEGROWTH = 10%)
GO
ALTER DATABASE [PSI_WMS] SET COMPATIBILITY_LEVEL = 130
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [PSI_WMS].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [PSI_WMS] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [PSI_WMS] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [PSI_WMS] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [PSI_WMS] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [PSI_WMS] SET ARITHABORT OFF 
GO
ALTER DATABASE [PSI_WMS] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [PSI_WMS] SET AUTO_SHRINK ON 
GO
ALTER DATABASE [PSI_WMS] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [PSI_WMS] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [PSI_WMS] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [PSI_WMS] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [PSI_WMS] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [PSI_WMS] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [PSI_WMS] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [PSI_WMS] SET  DISABLE_BROKER 
GO
ALTER DATABASE [PSI_WMS] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [PSI_WMS] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [PSI_WMS] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [PSI_WMS] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [PSI_WMS] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [PSI_WMS] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [PSI_WMS] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [PSI_WMS] SET RECOVERY SIMPLE 
GO
ALTER DATABASE [PSI_WMS] SET  MULTI_USER 
GO
ALTER DATABASE [PSI_WMS] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [PSI_WMS] SET DB_CHAINING OFF 
GO
ALTER DATABASE [PSI_WMS] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [PSI_WMS] SET TARGET_RECOVERY_TIME = 0 SECONDS 
GO
ALTER DATABASE [PSI_WMS] SET DELAYED_DURABILITY = DISABLED 
GO
ALTER DATABASE [PSI_WMS] SET QUERY_STORE = OFF
GO
USE [PSI_WMS]
GO
ALTER DATABASE SCOPED CONFIGURATION SET LEGACY_CARDINALITY_ESTIMATION = OFF;
GO
ALTER DATABASE SCOPED CONFIGURATION SET MAXDOP = 0;
GO
ALTER DATABASE SCOPED CONFIGURATION SET PARAMETER_SNIFFING = ON;
GO
ALTER DATABASE SCOPED CONFIGURATION SET QUERY_OPTIMIZER_HOTFIXES = OFF;
GO
USE [PSI_WMS]
GO
/****** Object:  User [user-macro]    Script Date: 2025-04-28 7:54:02 AM ******/
CREATE USER [user-macro] FOR LOGIN [user-macro] WITH DEFAULT_SCHEMA=[dbo]
GO
/****** Object:  User [userauto]    Script Date: 2025-04-28 7:54:02 AM ******/
CREATE USER [userauto] FOR LOGIN [userauto] WITH DEFAULT_SCHEMA=[dbo]
GO
/****** Object:  User [sawms]    Script Date: 2025-04-28 7:54:02 AM ******/
CREATE USER [sawms] FOR LOGIN [sawms] WITH DEFAULT_SCHEMA=[dbo]
GO
ALTER ROLE [db_datareader] ADD MEMBER [user-macro]
GO
ALTER ROLE [db_owner] ADD MEMBER [sawms]
GO
/****** Object:  UserDefinedFunction [dbo].[fun_delv_location_be4]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE FUNCTION [dbo].[fun_delv_location_be4](    
@wh varchar(50), @reffno varchar(21))

RETURNS varchar(50)
AS 
BEGIN
	DECLARE @toret VARCHAR(50);
		
	set @toret = (select TOP 1 ITH_LOC from ITH_TBL WHERE ITH_SER=@reffno AND ITH_WH=@wh AND ITH_QTY >0 ORDER BY ITH_LUPDT DESC);	--
    RETURN @toret;
END;
GO
/****** Object:  UserDefinedFunction [dbo].[fun_ithline]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[fun_ithline](    
)
RETURNS varchar(25)
AS 
BEGIN
	DECLARE @toret VARCHAR(25);
	DECLARE @ttlrow bigint;
	set @toret = 'ITH';
	set @ttlrow = (select MAX(CONVERT(INT, ISNULL(SUBSTRING(ITH_LINE,10,LEN(ITH_LINE)-9),0))) from ITH_TBL WHERE ITH_LINE!='' AND ITH_LINE LIKE 'ITH%' AND ITH_LINE IS NOT NULL AND ITH_DATE=CONVERT(DATE, GETDATE()))+1;	--	
	
    RETURN concat(@toret,CONVERT(VARCHAR, GETDATE(),12), @ttlrow);
END;
GO
/****** Object:  UserDefinedFunction [dbo].[sato_fun_get_id]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE FUNCTION [dbo].[sato_fun_get_id](
@status varchar(50)    
)
RETURNS varchar(25)
AS 
BEGIN
DECLARE @ttlrow bigint;
DECLARE @RETUN VARCHAR(20);
declare @trf varchar(20);
DECLARE @dt varchar(10);
DECLARE @ith_doc varchar(10);
DECLARE @serial bigint;
set @dt  =  convert(varchar(10),Getdate(),112)

	IF(@status = 'PendingRM')
	BEGIN
	
		set @ttlrow = (select max(Right(PNDSCN_ID,9)) from PNDSCN_TBL WHERE LEFT(PNDSCN_ID,8)=@dt);	--
		if @ttlrow is null
			BEGIN
				SET @ttlrow= CONVERT(BIGINT,(@dt+'000000001'))
			END
		ELSE
			BEGIN
				SET @ttlrow = @ttlrow+1
				SET @ttlrow= CONVERT(BIGINT,(@dt + right('00000000'+ convert(varchar(9),@ttlrow),9)))
			END
			SET @RETUN = CONVERT(VARCHAR,@ttlrow);
	END

	ELSE IF(@status = 'ScrapRM')
	BEGIN
	
	set @ttlrow = (select max(Right(SCRSCN_ID,9)) from SCRSCN_TBL WHERE LEFT(SCRSCN_ID,8)=@dt);	--
		if @ttlrow is null
			BEGIN
				SET @ttlrow= CONVERT(BIGINT,(@dt+'000000001'))
			END
		ELSE
			BEGIN
				SET @ttlrow = @ttlrow+1
				SET @ttlrow= CONVERT(BIGINT,(@dt + right('00000000'+ convert(varchar(9),@ttlrow),9)))
			END
    SET @RETUN = CONVERT(VARCHAR,@ttlrow);
	END

	ELSE IF(@status = 'TransferLocRM')
	BEGIN
	
	set @ith_doc = convert(varchar(10),concat('TRF-',FORMAT(Getdate(), 'yyMM'),'-'));

	set @trf = (select max(Right(ITH_DOC,9)) from ITH_TBL WHERE LEFT(ITH_DOC,9)=@ith_doc);

		if @trf is null
			BEGIN
				SET @trf = CONCAT(@ith_doc,'000000001')
			END
		ELSE
			BEGIN
				SET @serial = convert(bigint, @trf)
				SET @serial = @serial + 1
				SET @trf = CONVERT(VARCHAR,CONCAT(@ith_doc,right('00000000'+ convert(varchar(9),@serial),9)))
			END
       SET @RETUN = @trf;
	END



	ELSE IF(@status = 'TransferLocSCNFG')
	BEGIN
	
	set @ith_doc = convert(varchar(10),concat('TRF-',FORMAT(Getdate(), 'yyMM'),'-'));

	set @trf = (select max(Right([TRFFGSCN_ID],5)) from [TRFFGSCN_TBL] WHERE LEFT([TRFFGSCN_ID],9)=@ith_doc);

		if @trf is null
			BEGIN
				SET @trf = CONCAT(@ith_doc,'000000001')
			END
		ELSE
			BEGIN
				SET @serial = convert(bigint, @trf)
				SET @serial = @serial + 1
				SET @trf = CONVERT(VARCHAR,CONCAT(@ith_doc,right('00000000'+ convert(varchar(9),@serial),9)))
			END
       SET @RETUN = @trf;
	END

	ELSE IF(@status = 'AdjustRM')
	BEGIN
	
	set @ith_doc = convert(varchar(10),concat('ADJ-',FORMAT(Getdate(), 'yyMM'),'-'));

	set @trf = (select max(Right([ITH_DOC],9)) from [ITH_TBL] WHERE LEFT([ITH_DOC],9)=@ith_doc);

		if @trf is null
			BEGIN
				SET @trf = CONCAT(@ith_doc,'000000001')
			END
		ELSE
			BEGIN
				SET @serial = convert(bigint, @trf)
				SET @serial = @serial + 1
				SET @trf = CONVERT(VARCHAR,CONCAT(@ith_doc,right('00000000'+ convert(varchar(9),@serial),9)))
			END
       SET @RETUN = @trf;
	END
	
	ELSE IF(@status = 'RcvFG')
	BEGIN
	
	set @ttlrow = (select max(Right(RCVFGSCN_ID,9)) from RCVFGSCN_TBL WHERE LEFT(RCVFGSCN_ID,8)=@dt);	--
		if @ttlrow is null
			BEGIN
				SET @ttlrow= CONVERT(BIGINT,(@dt+'000000001'))
			END
		ELSE
			BEGIN
				SET @ttlrow = @ttlrow+1
				SET @ttlrow= CONVERT(BIGINT,(@dt + right('00000000'+ convert(varchar(9),@ttlrow),9)))
			END
    SET @RETUN = CONVERT(VARCHAR,@ttlrow);
	END
	ELSE IF(@status = 'RcvRM')
	BEGIN
	
	set @ttlrow = (select max(Right(RCVSCN_ID,9)) from RCVSCN_TBL WHERE LEFT(RCVSCN_ID,8)=@dt);	--
		if @ttlrow is null
			BEGIN
				SET @ttlrow= CONVERT(BIGINT,(@dt+'000000001'))
			END
		ELSE
			BEGIN
				SET @ttlrow = @ttlrow+1
				SET @ttlrow= CONVERT(BIGINT,(@dt + right('00000000'+ convert(varchar(9),@ttlrow),9)))
			END
    SET @RETUN = CONVERT(VARCHAR,@ttlrow);
	END
	ELSE IF(@status = 'PSNRM')
	BEGIN
	
	set @ttlrow = (select max(Right(SPLSCN_ID,9)) from SPLSCN_TBL WHERE LEFT(SPLSCN_ID,8)=@dt);	--
		if @ttlrow is null
			BEGIN
				SET @ttlrow= CONVERT(BIGINT,(@dt+'000000001'))
			END
		ELSE
			BEGIN
				SET @ttlrow = @ttlrow+1
				SET @ttlrow= CONVERT(BIGINT,(@dt + right('00000000'+ convert(varchar(9),@ttlrow),9)))
			END
    SET @RETUN = CONVERT(VARCHAR,@ttlrow);
	END

RETURN CONVERT(VARCHAR,@RETUN);
		
END;

GO
/****** Object:  UserDefinedFunction [dbo].[sato_wh_rcvtbl]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

create FUNCTION [dbo].[sato_wh_rcvtbl](
@Donoscan 	[varchar](100),
@item		[varchar](50)   
)
RETURNS varchar(25)
AS 
BEGIN
DECLARE @wh VARCHAR(20);

set @wh = (select TOP 1 RCV_WH from RCV_TBL WHERE RCV_DONO='' + @Donoscan + ''  AND RCV_ITMCD=@item);--

RETURN @WH;
		
END;
GO
/****** Object:  Table [dbo].[SER_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SER_TBL](
	[SER_ID] [varchar](21) NOT NULL,
	[SER_DOC] [varchar](50) NULL,
	[SER_ITMID] [varchar](50) NULL,
	[SER_QTY] [decimal](12, 3) NULL,
	[SER_QTYLOT] [bigint] NULL,
	[SER_LOTNO] [varchar](50) NULL,
	[SER_REFNO] [varchar](50) NULL,
	[SER_SHEET] [int] NULL,
	[SER_PRDDT] [date] NULL,
	[SER_PRDSHFT] [varchar](50) NULL,
	[SER_PRDLINE] [varchar](100) NULL,
	[SER_RMRK] [varchar](200) NULL,
	[SER_DOCTYPE] [char](1) NULL,
	[SER_ROHS] [char](1) NULL,
	[SER_CNTRYID] [varchar](2) NULL,
	[SER_FORCUST] [char](3) NULL,
	[SER_RAWTXT] [varchar](200) NULL,
	[SER_CAT] [char](1) NULL,
	[SER_BSGRP] [varchar](50) NULL,
	[SER_CUSCD] [varchar](50) NULL,
	[SER_GORNG] [char](1) NULL,
	[SER_RMUSE_COMFG] [char](10) NULL,
	[SER_RMUSE_COMFG_DT] [datetime] NULL,
	[SER_RMUSE_COMFG_USRID] [varchar](50) NULL,
	[SER_GRADE] [varchar](10) NULL,
	[SER_SIMBASE] [char](1) NULL,
	[SER_LUPDT] [datetime] NULL,
	[SER_USRID] [varchar](50) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
 CONSTRAINT [PK_SRFG_TBL] PRIMARY KEY CLUSTERED 
(
	[SER_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[ITH_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[ITH_TBL](
	[ITH_ITMCD] [varchar](50) NOT NULL,
	[ITH_DATE] [date] NULL,
	[ITH_FORM] [varchar](50) NULL,
	[ITH_DOC] [varchar](200) NULL,
	[ITH_QTY] [decimal](12, 3) NULL,
	[ITH_WH] [varchar](50) NULL,
	[ITH_LOC] [varchar](50) NULL,
	[ITH_SER] [varchar](21) NULL,
	[ITH_REMARK] [varchar](50) NULL,
	[ITH_LINE] [varchar](50) NULL,
	[ITH_EXPORTED] [char](1) NULL,
	[ITH_LUPDT] [datetime] NULL,
	[ITH_USRID] [varchar](9) NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[vstocktake]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE VIEW [dbo].[vstocktake] as
select vstock.*  from --, vincnew.ITH_SER NEWINCSER,vjoin.ITH_SER JOINSER,vsplit.ITH_SER SPLITSER
(
select b.* from --v1.*,SER_ITMID
(select ITH_WH,ITH_SER,SUM(ITH_QTY) TTLQTY, MAX(ITH_LUPDT) ITH_LUPDT from ITH_TBL WHERE ITH_WH='AFWH3' 
GROUP BY ITH_WH,ITH_SER
HAVING SUM(ITH_QTY) >0
)
b) vstock 
left join (
SELECT  ITH_QTY,ITH_SER
		 FROM ITH_TBL inner join SER_TBL 
		on ITH_SER=SER_ID 			
		WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN '2020-08-01 07:00:00' AND '2020-08-05 06:59:00')
		 AND ITH_FORM='INC-WH-FG'  
	
) vincnew on vstock.ITH_SER=vincnew.ITH_SER
left join (
SELECT  ITH_QTY,ITH_SER,ITH_LUPDT,ITH_LOC
		 FROM ITH_TBL  				
		WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN '2020-08-01 07:00:00' AND '2020-08-05 06:59:00')
		 AND ITH_FORM LIKE '%JOIN%'	
) vjoin on vstock.ITH_SER=vjoin.ITH_SER
left join (
SELECT  ITH_QTY,ITH_SER,ITH_LUPDT,ITH_LOC
		 FROM ITH_TBL  				
		WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN '2020-08-01 07:00:00' AND '2020-08-05 06:59:00')
		 AND ITH_FORM LIKE '%SPLIT%'	
) vsplit on vstock.ITH_SER=vsplit.ITH_SER

left join (
SELECT  ITH_QTY,ITH_SER,ITH_LUPDT,ITH_LOC
		 FROM ITH_TBL  				
		WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN '2020-08-01 07:00:00' AND '2020-08-05 06:59:00')
		 AND ITH_FORM LIKE '%CANCEL%'	
) vcancel on vstock.ITH_SER=vcancel.ITH_SER
where vincnew.ITH_SER is null AND vjoin.ITH_SER IS NULL AND vsplit.ITH_SER IS NULL
and vcancel.ITH_SER is null

--select a.* from VFG_INVENTORY a
GO
/****** Object:  Table [dbo].[EMPACCESS_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[EMPACCESS_TBL](
	[EMPACCESS_GRPID] [varchar](20) NOT NULL,
	[EMPACCESS_MENUID] [varchar](15) NOT NULL,
	[EMPACCESS_USRADD] [varchar](50) NULL,
	[EMPACCESS_TIMEADD] [datetime] NULL,
	[updated_at] [datetime2](7) NULL,
 CONSTRAINT [PK__EMPACCES__7CAE6D4572E29542] PRIMARY KEY CLUSTERED 
(
	[EMPACCESS_GRPID] ASC,
	[EMPACCESS_MENUID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MENU_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MENU_TBL](
	[MENU_ID] [varchar](15) NOT NULL,
	[MENU_NAME] [varchar](100) NULL,
	[MENU_DSCRPTN] [varchar](100) NULL,
	[MENU_PRNT] [varchar](15) NULL,
	[MENU_URL] [varchar](100) NULL,
	[MENU_ICON] [varchar](50) NULL,
	[MENU_STT] [varchar](7) NULL,
	[MENU_HTID] [varchar](15) NULL,
	[MENU_HTIDDESC] [varchar](35) NULL,
	[MENU_APP] [varchar](3) NULL,
	[MENU_DESKTOP] [varchar](50) NULL,
 CONSTRAINT [PK_MENU_TBL] PRIMARY KEY CLUSTERED 
(
	[MENU_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MSTEMP_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MSTEMP_TBL](
	[MSTEMP_ID] [varchar](9) NOT NULL,
	[MSTEMP_FNM] [varchar](50) NULL,
	[MSTEMP_LNM] [varchar](50) NULL,
	[MSTEMP_NIK] [varchar](25) NULL,
	[MSTEMP_PW] [varchar](100) NULL,
	[MSTEMP_GRP] [varchar](20) NULL,
	[MSTEMP_ACTSTS] [bit] NULL,
	[MSTEMP_STS] [bit] NULL,
	[MSTEMP_DACTTM] [datetime] NULL,
	[MSTEMP_REGTM] [datetime] NULL,
	[MSTEMP_PWHT] [varchar](8) NULL,
	[MSTEMP_IP] [varchar](15) NULL,
	[MSTEMP_ACTTM] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[MSTEMP_LCHGPWDT] [datetime] NULL,
 CONSTRAINT [PK_MSTEMP_TBL] PRIMARY KEY CLUSTERED 
(
	[MSTEMP_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[sato_user_role]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[sato_user_role] as
select MSTEMP_ID UserId, MENU_HTID role_id  from MSTEMP_TBL inner join EMPACCESS_TBL on
MSTEMP_GRP=EMPACCESS_GRPID	inner join
MENU_TBL on EMPACCESS_MENUID=MENU_ID
where coalesce(MENU_HTID,'') != '' 
GO
/****** Object:  View [dbo].[VFG_INVENTORY]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[VFG_INVENTORY] AS
select * from SMTTrace.dbo.WMS_Inv
GO
/****** Object:  View [dbo].[vinv_n_manual]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO







CREATE view [dbo].[vinv_n_manual] as
select b1.* from
(select ISNULL(Refno, ReffNumber) thereff
,ISNULL(a.cQty,b.Qty) theqty
,ISNULL(a.cLoc,b.Location) theloc
from VFG_INVENTORY a
full outer join manualinv072020 b
on a.Refno=b.ReffNumber) b1
left join (
SELECT  ITH_QTY,ITH_SER,ITH_LUPDT,ITH_LOC
		 FROM ITH_TBL  				
		WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN '2020-08-05 07:00:00' AND '2020-08-05 06:59:00')
		 AND ITH_FORM LIKE '%JOIN%'	
) vjoin on b1.thereff=vjoin.ITH_SER
left join (
SELECT  ITH_QTY,ITH_SER,ITH_LUPDT,ITH_LOC
		 FROM ITH_TBL  				
		WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN '2020-08-05 07:00:00' AND '2020-08-05 06:59:00')
		 AND ITH_FORM LIKE '%SPLIT%'	
) vsplit on b1.thereff=vsplit.ITH_SER
left join (
SELECT  ITH_QTY,ITH_SER,ITH_LUPDT,ITH_LOC
		 FROM ITH_TBL  				
		WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN '2020-08-05 07:00:00' AND '2020-08-05 06:59:00')
		 AND ITH_FORM LIKE '%CANCEL%'	
) vcancel on thereff=vcancel.ITH_SER

left join ( ---warning use this when adding inventory
SELECT  ITH_QTY,ITH_SER,ITH_LUPDT,ITH_LOC
		 FROM ITH_TBL  				
		WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN '2020-08-05 07:00:00' AND '2020-08-05 06:59:00')
		 AND ITH_FORM LIKE '%OUT-WH-FG%'	
) vdelv on thereff=vdelv.ITH_SER

WHERE vjoin.ITH_SER IS NULL AND vsplit.ITH_SER IS NULL 
and vcancel.ITH_SER is null 
AND vdelv.ITH_SER IS NULL
GO
/****** Object:  View [dbo].[User_ht]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE view [dbo].[User_ht] as
select MSTEMP_ID UserId, MSTEMP_PWHT Passwd, MSTEMP_FNM Name, MSTEMP_GRP Authority from MSTEMP_TBL where MSTEMP_STS='1'
GO
/****** Object:  View [dbo].[vstocktake_foradding]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE VIEW [dbo].[vstocktake_foradding] as
select vstock.*  from --, vincnew.ITH_SER NEWINCSER,vjoin.ITH_SER JOINSER,vsplit.ITH_SER SPLITSER
(
select b.* from --v1.*,SER_ITMID
(select ITH_WH,ITH_SER,SUM(ITH_QTY) TTLQTY, MAX(ITH_LUPDT) ITH_LUPDT from ITH_TBL WHERE ITH_WH='AFWH3' 
GROUP BY ITH_WH,ITH_SER
HAVING SUM(ITH_QTY) >0
)
b) vstock 


--select a.* from VFG_INVENTORY a
GO
/****** Object:  View [dbo].[sato_mst_role_user]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[sato_mst_role_user] as
select MENU_HTID role_id, MENU_DSCRPTN rore_name from MENU_TBL where coalesce(MENU_HTID,'') != '' 
GO
/****** Object:  View [dbo].[XMCUS]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XMCUS] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.MCUS_TBL
GO
/****** Object:  View [dbo].[XSSO2]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--select * from SRVMEGA.PSI_MEGAEMS.dbo.SCPOD1_TBL where SCPOD1_ORDNO='SME-20-00088105'
--select * from SRVMEGA.PSI_MEGAEMS.dbo.SCPOD2_TBL WHERE SCPOD2_CPONO='SME-20-00088105'
--ORDER BY SCPOD2_MDLCD ASC
CREATE View [dbo].[XSSO2] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.SSO2_TBL 
GO
/****** Object:  View [dbo].[XEPROCUSTOMER_V]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XEPROCUSTOMER_V] AS
select SSO2_CUSCD,MCUS_CUSNM,MCUS_CURCD from
(select SSO2_CUSCD from XSSO2 
WHERE SSO2_BSGRP LIKE '%IEP%' AND SSO2_CUSCD not LIKE '%kyk%'
GROUP BY SSO2_BSGRP,SSO2_CUSCD) v1 left join XMCUS on SSO2_CUSCD=MCUS_CUSCD
GO
/****** Object:  View [dbo].[vstock]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[vstock] as
SELECT ITH_ITMCD,sum(ITH_QTY) ttl from ITH_TBL
group by ITH_ITMCD
GO
/****** Object:  Table [dbo].[MSUP_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MSUP_TBL](
	[MSUP_SUPCD] [char](10) NOT NULL,
	[MSUP_SUPCR] [char](4) NOT NULL,
	[MSUP_SUPNM] [char](50) NULL,
	[MSUP_ABBRV] [char](25) NULL,
	[MSUP_SUPTY] [char](10) NULL,
	[MSUP_CTRCD] [char](10) NULL,
	[MSUP_PTERM] [char](10) NULL,
	[MSUP_TAXCD] [char](5) NULL,
	[MSUP_ADDR1] [char](150) NULL,
	[MSUP_ADDR2] [char](50) NULL,
	[MSUP_ADDR3] [char](50) NULL,
	[MSUP_ADDR4] [char](50) NULL,
	[MSUP_ADDR5] [char](50) NULL,
	[MSUP_ADDR6] [char](50) NULL,
	[MSUP_ARECD] [char](10) NULL,
	[MSUP_TELNO] [varchar](50) NULL,
	[MSUP_TELNO2] [varchar](50) NULL,
	[MSUP_FAXNO] [varchar](50) NULL,
	[MSUP_FAXNO2] [varchar](50) NULL,
	[MSUP_TELEX] [char](20) NULL,
	[MSUP_EMAIL] [char](40) NULL,
	[MSUP_PIC] [char](40) NULL,
	[MSUP_PIC2] [char](40) NULL,
	[MSUP_LPODT] [datetime] NULL,
	[MSUP_REM] [char](50) NULL,
	[MSUP_REVISE] [char](1) NULL,
	[MSUP_PAYTY] [char](10) NULL,
	[MSUP_PLTDAY] [int] NULL,
	[MSUP_AIRLT] [int] NULL,
	[MSUP_SPFG] [char](1) NULL,
	[MSUP_LUPDT] [datetime] NULL,
	[MSUP_POTO] [char](10) NULL,
	[MSUP_POCUR] [char](4) NULL,
	[MSUP_SACTY] [char](8) NULL,
	[MSUP_USRID] [char](10) NULL,
	[MSUP_CRLMT] [decimal](13, 2) NULL,
	[MSUP_TAXREG] [char](25) NULL,
	[MSUP_TRADE] [char](1) NULL,
	[MSUP_SNMCD] [char](10) NULL,
	[MSUP_AMTDEC] [int] NULL,
	[MSUP_AMTROUND] [char](1) NULL,
	[MSUP_PAYEECD] [char](10) NULL,
	[MSUP_TOBANKMSG] [char](140) NULL,
	[MSUP_SUPNM1] [varchar](50) NULL,
	[MSUP_PRGID] [varchar](50) NULL,
 CONSTRAINT [PK_IMSUP_TBL] PRIMARY KEY NONCLUSTERED 
(
	[MSUP_SUPCD] ASC,
	[MSUP_SUPCR] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MCUS_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MCUS_TBL](
	[MCUS_CUSCD] [char](10) NOT NULL,
	[MCUS_CURCD] [char](4) NOT NULL,
	[MCUS_CUSNM] [char](50) NULL,
	[MCUS_ABBRV] [char](25) NULL,
	[MCUS_CTRCD] [char](10) NULL,
	[MCUS_CUSTY] [char](10) NULL,
	[MCUS_TAXCD] [char](5) NULL,
	[MCUS_PTERM] [char](10) NULL,
	[MCUS_PTYPE] [char](10) NULL,
	[MCUS_ADDR1] [char](50) NULL,
	[MCUS_ADDR2] [char](50) NULL,
	[MCUS_ADDR3] [char](50) NULL,
	[MCUS_ADDR4] [char](50) NULL,
	[MCUS_ADDR5] [char](50) NULL,
	[MCUS_ADDR6] [char](50) NULL,
	[MCUS_TELNO] [char](20) NULL,
	[MCUS_TELNO2] [char](20) NULL,
	[MCUS_FAXNO] [char](20) NULL,
	[MCUS_FAXNO2] [char](20) NULL,
	[MCUS_TELEX] [char](20) NULL,
	[MCUS_EMAIL] [char](20) NULL,
	[MCUS_PIC] [char](40) NULL,
	[MCUS_PIC2] [char](40) NULL,
	[MCUS_CRCHK] [char](1) NULL,
	[MCUS_CRLMT] [decimal](13, 2) NULL,
	[MCUS_CREXP] [datetime] NULL,
	[MCUS_REM] [char](50) NULL,
	[MCUS_LCLMT] [decimal](13, 2) NULL,
	[MCUS_DLTIME] [int] NULL,
	[MCUS_ARECD] [char](10) NULL,
	[MCUS_IVCUS] [char](10) NULL,
	[MCUS_IVCUR] [char](4) NULL,
	[MCUS_LUPDT] [datetime] NULL,
	[MCUS_DELCD] [char](10) NULL,
	[MCUS_CACTY] [char](8) NULL,
	[MCUS_NPWP] [varchar](50) NULL,
	[MCUS_USRID] [char](10) NULL,
	[MCUS_TAXREG] [char](25) NULL,
	[MCUS_TRADE] [char](1) NULL,
	[MCUS_NOSIGN] [int] NOT NULL,
	[MCUS_CUSNM1] [varchar](50) NULL,
	[MCUS_PRGID] [varchar](50) NULL,
	[MCUS_TPBTYPE] [varchar](2) NULL,
	[MCUS_TPBNO] [varchar](45) NULL,
 CONSTRAINT [PK_IMCUS_TBL] PRIMARY KEY NONCLUSTERED 
(
	[MCUS_CUSCD] ASC,
	[MCUS_CURCD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[v_supplier_customer_union]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE   view [dbo].[v_supplier_customer_union] as
select * from
(
select rtrim(MSUP_SUPCD) MSUP_SUPCD, RTRIM(MSUP_SUPNM) MSUP_SUPNM, RTRIM(MSUP_SUPCR) MSUP_SUPCR,RTRIM(MSUP_ADDR1)  MSUP_ADDR1,RTRIM(MSUP_TELNO) MSUP_TELNO,rtrim(MSUP_TAXREG) MSUP_TAXREG from MSUP_TBL
UNION
select rtrim(MCUS_CUSCD) MSUP_SUPCD, RTRIM(MCUS_CUSNM) MSUP_SUPNM, RTRIM(MCUS_CURCD) MSUP_SUPCR,RTRIM(MCUS_ADDR1)  MSUP_ADDR1,RTRIM(MCUS_TELNO) MSUP_TELNO,rtrim(MCUS_TAXREG) MSUP_TAXREG from MCUS_TBL
) v1


GO
/****** Object:  Table [dbo].[MITM_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MITM_TBL](
	[MITM_ITMCD] [char](50) NOT NULL,
	[MITM_ITMD1] [varchar](150) NULL,
	[MITM_ITMD2] [char](50) NULL,
	[MITM_ITMTY] [char](10) NULL,
	[MITM_ITMFG] [char](1) NULL,
	[MITM_MODEL] [char](1) NULL,
	[MITM_STKUOM] [char](10) NULL,
	[MITM_MOQ] [decimal](12, 3) NULL,
	[MITM_SPQ] [decimal](12, 3) NULL,
	[MITM_MINLV] [decimal](12, 3) NULL,
	[MITM_MAXLV] [decimal](12, 3) NULL,
	[MITM_DICHK] [decimal](12, 3) NULL,
	[MITM_BUFPC] [decimal](5, 2) NULL,
	[MITM_FRRTE] [decimal](7, 4) NULL,
	[MITM_PLTDAY] [int] NULL,
	[MITM_ETALT] [int] NULL,
	[MITM_SUPCD] [char](10) NULL,
	[MITM_SUPCR] [char](4) NULL,
	[MITM_SPTNO] [char](80) NULL,
	[MITM_PUPRC] [decimal](15, 6) NULL,
	[MITM_MKECD] [char](10) NULL,
	[MITM_GRSWG] [decimal](8, 3) NULL,
	[MITM_NETWG] [decimal](8, 3) NULL,
	[MITM_WGTUM] [char](10) NULL,
	[MITM_STRUOM] [char](10) NULL,
	[MITM_LBCTL] [char](1) NULL,
	[MITM_RJPAT] [char](1) NULL,
	[MITM_NOUBL] [int] NULL,
	[MITM_LIFE] [int] NULL,
	[MITM_SPQFG] [char](1) NULL,
	[MITM_LUPDT] [datetime] NULL,
	[MITM_REM1] [char](50) NULL,
	[MITM_REM2] [char](50) NULL,
	[MITM_REM3] [char](50) NULL,
	[MITM_FSFLG] [char](1) NULL,
	[MITM_HSCD] [varchar](20) NULL,
	[MITM_SECCD] [char](10) NULL,
	[MITM_PSNCD] [char](10) NULL,
	[MITM_USRID] [char](10) NULL,
	[MITM_HUBFG] [char](1) NULL,
	[MITM_EHSUP] [char](12) NULL,
	[MITM_ROHS1] [datetime] NULL,
	[MITM_ROHS2] [datetime] NULL,
	[MITM_ROHS3] [datetime] NULL,
	[MITM_ROHS4] [datetime] NULL,
	[MITM_ROHS5] [datetime] NULL,
	[MITM_ROHS6] [datetime] NULL,
	[MITM_ROHSALL] [char](20) NULL,
	[MITM_ROHSNOW] [char](7) NULL,
	[MITM_PCAT1] [char](10) NULL,
	[MITM_PCAT2] [char](10) NULL,
	[MITM_STXCD] [char](50) NULL,
	[MITM_PGRP] [char](10) NULL,
	[MITM_ITMKY] [char](50) NULL,
	[MITM_MNMCD] [char](10) NULL,
	[MITM_LOTNOCTL] [char](1) NULL,
	[MITM_C3LBCTL] [char](1) NULL,
	[MITM_CEIFG] [char](1) NULL,
	[MITM_CEIAPPLYNO] [char](25) NULL,
	[MITM_CEIAPPLYDT] [datetime] NULL,
	[MITM_ACTIVE] [char](1) NULL,
	[MITM_RQRLSNO] [char](25) NULL,
	[MITM_PRGID] [char](20) NULL,
	[MITM_ORIGIN] [char](10) NULL,
	[MITM_TARIFF] [decimal](5, 2) NULL,
	[MITM_CIMITMCD] [char](50) NULL,
	[MITM_GRADE] [char](10) NULL,
	[MITM_ISLED] [char](1) NULL,
	[MITM_LEDGRP] [char](50) NULL,
	[MITM_PODCD] [char](10) NULL,
	[MITM_POUOM] [char](10) NULL,
	[MITM_SPECNO] [varchar](10) NULL,
	[MITM_NICKNM] [varchar](50) NULL,
	[MITM_MAKERNM] [varchar](25) NULL,
	[MITM_SN] [varchar](10) NULL,
	[MITM_REMARK] [varchar](100) NULL,
	[MITM_MSNO] [varchar](20) NULL,
	[MITM_SEITSUREM] [varchar](100) NULL,
	[MITM_EOLLASTBUYID] [varchar](20) NULL,
	[MITM_EOLLASTBUYREM] [varchar](100) NULL,
	[MITM_EOLLASTBUYPONO] [varchar](20) NULL,
	[MITM_PARTCAT] [varchar](10) NULL,
	[MITM_MAJORMODEL] [varchar](30) NULL,
	[MITM_PROCESS] [varchar](5) NULL,
	[MITM_SECPRDNO] [varchar](25) NULL,
	[MITM_MODELCAT] [varchar](10) NULL,
	[MITM_PCBASTS] [varchar](10) NULL,
	[MITM_PCBAREM] [varchar](100) NULL,
	[MITM_ASPSCHEDULE] [varchar](10) NULL,
	[MITM_ASPEOLREM] [varchar](100) NULL,
	[MITM_CAVITY] [int] NULL,
	[MITM_MCPOINT] [int] NULL,
	[MITM_HWPOINT] [int] NULL,
	[MITM_QTYPHR] [int] NULL,
	[MITM_LASTBUY] [char](1) NULL,
	[MITM_RELATEDTEN] [varchar](100) NULL,
	[MITM_RUNFG] [char](1) NULL,
	[MITM_CHGPOFG] [varchar](30) NULL,
	[MITM_INDIRMT] [char](1) NULL,
	[MITM_HSCODET] [varchar](20) NULL,
	[MITM_GWG] [decimal](6, 6) NULL,
	[MITM_NWG] [decimal](12, 6) NULL,
	[MITM_BOXTYPE] [varchar](15) NULL,
	[MITM_MXHEIGHT] [numeric](2, 0) NULL,
	[MITM_MXLENGTH] [numeric](2, 0) NULL,
	[MITM_LBLCLR] [varchar](35) NULL,
	[MITM_SHTQTY] [int] NULL,
	[MITM_BM] [decimal](18, 1) NULL,
	[MITM_PPN] [decimal](18, 1) NULL,
	[MITM_PPH] [decimal](18, 1) NULL,
	[MITM_ITMCDCUS] [varchar](50) NULL,
	[MITM_NCAT] [varchar](20) NULL,
	[MITM_NTYPE] [varchar](20) NULL,
	[MITM_BOXWEIGHT] [decimal](15, 6) NULL,
	[MITM_PMNO] [int] NULL,
	[MITM_PMREGDT] [date] NULL,
	[MITM_PMCONSIGN] [varchar](50) NULL,
	[MITM_MDLCD] [varchar](25) NULL,
	[MITM_TYPECD] [varchar](25) NULL,
 CONSTRAINT [PK_MITM_TBL] PRIMARY KEY CLUSTERED 
(
	[MITM_ITMCD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SERD2_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SERD2_TBL](
	[SERD2_PSNNO] [varchar](50) NOT NULL,
	[SERD2_LINENO] [varchar](20) NOT NULL,
	[SERD2_PROCD] [varchar](25) NULL,
	[SERD2_CAT] [varchar](20) NOT NULL,
	[SERD2_FR] [char](1) NOT NULL,
	[SERD2_SER] [varchar](50) NOT NULL,
	[SERD2_FGQTY] [bigint] NULL,
	[SERD2_JOB] [varchar](50) NOT NULL,
	[SERD2_QTPER] [decimal](12, 3) NULL,
	[SERD2_MC] [varchar](20) NULL,
	[SERD2_MCZ] [varchar](20) NULL,
	[SERD2_ITMCD] [varchar](50) NOT NULL,
	[SERD2_QTY] [decimal](12, 3) NULL,
	[SERD2_LOTNO] [varchar](50) NOT NULL,
	[SERD2_MSCANTM] [datetime] NULL,
	[SERD2_REMARK] [varchar](25) NULL,
	[SERD2_USRID] [varchar](15) NULL,
	[SERD2_LUPDT] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[vserd2_cims]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO












CREATE view [dbo].[vserd2_cims] AS

SELECT VX.SERD2_PSNNO
	,VX.SERD2_LINENO
	,VX.SERD2_PROCD
	,VX.SERD2_CAT
	,VX.SERD2_FR
	,VX.SERD2_JOB
	,VX.SERD2_ITMCD
	,SERD2_FGQTY
	,CASE 
		WHEN CHARINDEX('IEI', VX.SERD2_PSNNO) > 0
			THEN CONVERT(FLOAT, SERD2_QTYSUM) / SERD2_FGQTY
			--THEN CASE 
			--		WHEN SERD2_QTYSUM < VX.SERD2_QTPER
			--			THEN SERD2_FGQTY * VX.SERD2_QTPER
			--		ELSE CONVERT(FLOAT, SERD2_QTYSUM) / SERD2_FGQTY
			--		END
		ELSE VX.SERD2_QTPER
		END SERD2_QTPER
	,VX.SERD2_MC
	,VX.SERD2_MCZ
	,SERD2_QTYSUM SERD2_QTY
	,VX.SERD2_SER
	,SERD2_LOTNO LOTNO
	,MITM_ITMD1
	,MITM_SPTNO
FROM (
	SELECT SERD2_PSNNO
		,SERD2_JOB
		,SERD2_LINENO
		,SERD2_PROCD
		,SERD2_CAT
		,SERD2_FR
		,SERD2_MC
		,SERD2_FGQTY
		,SERD2_MCZ
		,SERD2_QTPER
		,SUM(SERD2_QTY) SERD2_QTYSUM
		,SERD2_ITMCD
		,SERD2_SER
		,MIN(SERD2_LOTNO) SERD2_LOTNO
	FROM SERD2_TBL
	GROUP BY SERD2_PSNNO
		,SERD2_JOB
		,SERD2_LINENO
		,SERD2_CAT
		,SERD2_FR
		,SERD2_MC
		,SERD2_MCZ
		,SERD2_QTPER
		,SERD2_ITMCD
		,SERD2_FGQTY
		,SERD2_PROCD
		,SERD2_SER
	) VX
INNER JOIN (
	SELECT SERD2_PSNNO
		,SERD2_JOB
		,SERD2_LINENO
		,SERD2_PROCD
		,SERD2_CAT
		,SERD2_FR
		,SERD2_MC
		,SERD2_MCZ
		,SERD2_QTPER
		,SUM(SERD2_QTY) QTYPERMC
		,SERD2_SER
	FROM SERD2_TBL
	GROUP BY SERD2_PSNNO
		,SERD2_JOB
		,SERD2_LINENO
		,SERD2_CAT
		,SERD2_FR
		,SERD2_MC
		,SERD2_MCZ
		,SERD2_QTPER
		,SERD2_PROCD
		,SERD2_SER
	) VXH ON VX.SERD2_PSNNO = VXH.SERD2_PSNNO
	AND VX.SERD2_JOB = VXH.SERD2_JOB
	AND VX.SERD2_LINENO = VXH.SERD2_LINENO
	AND VX.SERD2_CAT = VXH.SERD2_CAT
	AND VX.SERD2_FR = VXH.SERD2_FR
	AND VX.SERD2_MC = VXH.SERD2_MC
	AND VX.SERD2_MCZ = VXH.SERD2_MCZ
	AND VX.SERD2_QTPER = VXH.SERD2_QTPER
	AND VX.SERD2_PROCD = VXH.SERD2_PROCD
	AND vx.SERD2_SER = VXH.SERD2_SER
LEFT JOIN MITM_TBL ON SERD2_ITMCD = MITM_ITMCD


GO
/****** Object:  Table [dbo].[DLV_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[DLV_TBL](
	[DLV_ID] [varchar](50) NOT NULL,
	[DLV_DATE] [date] NULL,
	[DLV_BSGRP] [varchar](15) NULL,
	[DLV_CUSTCD] [varchar](50) NULL,
	[DLV_CONSIGN] [varchar](25) NULL,
	[DLV_INVNO] [varchar](50) NULL,
	[DLV_INVDT] [date] NULL,
	[DLV_SMTINVNO] [varchar](50) NULL,
	[DLV_TRANS] [varchar](15) NULL,
	[DLV_DOCREFF] [varchar](50) NULL,
	[DLV_SER] [varchar](50) NOT NULL,
	[DLV_QTY] [decimal](12, 3) NOT NULL,
	[DLV_RMRK] [varchar](50) NULL,
	[DLV_ISPTTRND] [char](1) NULL,
	[DLV_DSCRPTN] [varchar](50) NULL,
	[DLV_NOPEN] [varchar](50) NULL,
	[DLV_RPDATE] [date] NULL,
	[DLV_BCTYPE] [varchar](15) NULL,
	[DLV_NOAJU] [varchar](6) NULL,
	[DLV_BCDATE] [date] NULL,
	[DLV_LUPDT] [datetime] NULL,
	[DLV_USRID] [varchar](50) NULL,
	[DLV_CRTD] [varchar](50) NULL,
	[DLV_CRTDTM] [datetime] NULL,
	[DLV_APPRV] [varchar](50) NULL,
	[DLV_APPRVTM] [datetime] NULL,
	[DLV_POST] [varchar](50) NULL,
	[DLV_POSTTM] [datetime] NULL,
	[DLV_RPLCMNT] [char](1) NULL,
	[DLV_VAT] [char](1) NULL,
	[DLV_KNBNDLV] [char](1) NULL,
	[DLV_FROMOFFICE] [varchar](6) NULL,
	[DLV_DESTOFFICE] [varchar](6) NULL,
	[DLV_PURPOSE] [varchar](50) NULL,
	[DLV_CUSTDO] [varchar](50) NULL,
	[DLV_ZJENIS_TPB_ASAL] [varchar](15) NULL,
	[DLV_ZJENIS_TPB_TUJUAN] [varchar](15) NULL,
	[DLV_SHP_CNFRM_BY] [varchar](50) NULL,
	[DLV_SHP_CNFRM_DT] [datetime] NULL,
	[DLV_ZSKB] [varchar](75) NULL,
	[DLV_ZTANGGAL_SKB] [date] NULL,
	[DLV_ZKODE_CARA_ANGKUT] [varchar](2) NULL,
	[DLV_ZID_MODUL] [varchar](6) NULL,
	[DLV_ITMCD] [varchar](50) NOT NULL,
	[DLV_ITMLOTNO] [varchar](50) NULL,
	[DLV_TYPE] [varchar](50) NULL,
	[DLV_CONA] [varchar](50) NULL,
	[DLV_LINE] [int] NULL,
	[DLV_ZNOMOR_AJU] [varchar](30) NULL,
	[DLV_CALCU_REMARK] [varchar](20) NULL,
	[DLV_CALCU_USR] [varchar](50) NULL,
	[DLV_CALCU_DT] [datetime] NULL,
	[DLV_LOCFR] [varchar](25) NULL,
	[DLV_RPRDOC] [varchar](50) NULL,
	[DLV_ITMD1] [varchar](100) NULL,
	[DLV_ITMSPTNO] [varchar](50) NULL,
	[DLV_PARENTDOC] [varchar](50) NULL,
	[DLV_SPPBDOC] [varchar](50) NULL,
 CONSTRAINT [PK__DLV_TBL__651BD181959AB4E9] PRIMARY KEY CLUSTERED 
(
	[DLV_ID] ASC,
	[DLV_SER] ASC,
	[DLV_ITMCD] ASC,
	[DLV_QTY] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[DLVSCR_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[DLVSCR_TBL](
	[DLVSCR_TXID] [varchar](50) NOT NULL,
	[DLVSCR_ITMID] [varchar](50) NOT NULL,
	[DLVSCR_ITMQT] [decimal](12, 3) NULL,
	[DLVSCR_PRPRC] [decimal](15, 6) NULL,
	[DLVSCR_LINE] [int] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MDEL_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MDEL_TBL](
	[MDEL_DELCD] [char](12) NOT NULL,
	[MDEL_CTRCD] [char](10) NULL,
	[MDEL_DELNM] [char](50) NULL,
	[MDEL_ADDR1] [char](50) NULL,
	[MDEL_ADDR2] [char](50) NULL,
	[MDEL_ADDR3] [char](50) NULL,
	[MDEL_ADDR4] [char](50) NULL,
	[MDEL_ADDR5] [char](50) NULL,
	[MDEL_ADDR6] [char](50) NULL,
	[MDEL_ARECD] [char](10) NULL,
	[MDEL_LUPDT] [datetime] NULL,
	[MDEL_USRID] [char](10) NULL,
	[MDEL_ADDRCUSTOMS] [varchar](150) NULL,
	[MDEL_ZSKEP] [varchar](50) NULL,
	[MDEL_ZNAMA] [varchar](100) NULL,
	[MDEL_ZTAX] [varchar](50) NULL,
	[MDEL_ATTN] [varchar](50) NULL,
	[MDEL_TXCD] [char](2) NULL,
	[MDEL_3RDP_AS] [char](1) NULL,
	[MDEL_ZSKEP_DATE] [date] NULL,
	[MDEL_NIB] [varchar](45) NULL,
	[PARENT_DELCD] [varchar](12) NULL,
	[MDEL_JENIS_TPB] [varchar](2) NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[wms_v_outpabean_scr]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[wms_v_outpabean_scr] as
SELECT DLV_BCDATE
	,DLV_ID
	,DLV_CUSTDO
	,DLV_CONSIGN
	,NOMAJU
	,NOMPEN
	,INVDT
	,DLV_INVNO
	,DLV_SMTINVNO
	,DLVSCR_ITMID SER_ITMID
	,MITM_ITMD1
	,'' DLVPRC_CPO
	,DLVPRC_QTY
	,DLVSCR_PRPRC DLVPRC_PRC
	,DLVSCR_PRPRC AMOUNT
	,MDEL_ZNAMA
	,MDEL_ADDRCUSTOMS
	,MITM_HSCD
	,DLV_BCTYPE
	,DLV_ZJENIS_TPB_TUJUAN
	,TGLPEN
	,MITM_STKUOM
	,DLV_PURPOSE
	,MITM_MODEL
	,DLV_SPPBDOC
	,VALUTA
FROM (
	SELECT DLV_BCDATE
		,DLV_ID
		,DLV_CUSTDO
		,DLV_CONSIGN
		,DLV_ZNOMOR_AJU NOMAJU
		,DLV_NOPEN NOMPEN
		,DLV_INVDT INVDT
		,DLV_INVNO
		,DLV_SMTINVNO
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_BCTYPE
		,DLV_ZJENIS_TPB_TUJUAN
		,DLV_RPDATE TGLPEN
		,DLV_PURPOSE
		,max(DLV_SPPBDOC) DLV_SPPBDOC
		,MAX(MCUS_CURCD) VALUTA
	FROM DLV_TBL
	LEFT JOIN MDEL_TBL ON DLV_CONSIGN = MDEL_DELCD
	LEFT JOIN MITM_TBL ON DLV_ITMCD = MITM_ITMCD
	LEFT JOIN MCUS_TBL ON DLV_CUSTCD = MCUS_CUSCD
	WHERE DLV_SER = ''
		AND MITM_MODEL = '8'
	GROUP BY DLV_BCDATE
		,DLV_ID
		,DLV_CUSTDO
		,DLV_CONSIGN
		,DLV_ZNOMOR_AJU
		,DLV_NOPEN
		,DLV_INVDT
		,DLV_INVNO
		,DLV_SMTINVNO
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_BCTYPE
		,DLV_ZJENIS_TPB_TUJUAN
		,DLV_RPDATE
		,DLV_PURPOSE
	) VDLV
LEFT JOIN (
	SELECT DLVSCR_TXID
		,DLVSCR_ITMID
		,RTRIM(MITM_ITMD1) MITM_ITMD1
		,sum(DLVSCR_ITMQT) DLVPRC_QTY
		,DLVSCR_PRPRC
		,MITM_HSCD
		,rtrim(MITM_STKUOM) MITM_STKUOM
		,MITM_MODEL
	FROM DLVSCR_TBL
	LEFT JOIN MITM_TBL ON DLVSCR_ITMID = MITM_ITMCD
	GROUP BY DLVSCR_TXID
		,DLVSCR_ITMID
		,MITM_ITMD1
		,DLVSCR_PRPRC
		,MITM_STKUOM
		,MITM_MODEL
		,MITM_HSCD
	) vselect ON DLV_ID = DLVSCR_TXID

GO
/****** Object:  View [dbo].[v_infoscntoday_whrtn]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_infoscntoday_whrtn]
AS
SELECT        a.ITH_ITMCD, b.MITM_ITMD1, a.ITH_DOC, a.ITH_QTY, b.MITM_STKUOM, c.MSTEMP_FNM, a.ITH_LUPDT, a.ITH_SER
FROM            dbo.ITH_TBL AS a INNER JOIN
                         dbo.MITM_TBL AS b ON a.ITH_ITMCD = b.MITM_ITMCD INNER JOIN
                         dbo.MSTEMP_TBL AS c ON a.ITH_USRID = c.MSTEMP_ID
WHERE        (a.ITH_DATE = CONVERT(date, GETDATE())) AND (a.ITH_FORM = 'INC-WHRTN-FG')
GO
/****** Object:  View [dbo].[XPIS1]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[XPIS1] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.PIS1_TBL
GO
/****** Object:  View [dbo].[XPPSN1]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


create VIEW [dbo].[XPPSN1]
AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.PPSN1_TBL

GO
/****** Object:  View [dbo].[XSIM_CHECKER]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE VIEW [dbo].[XSIM_CHECKER] AS
SELECT RTRIM(PIS1_DOCNO) DOCNO, RTRIM(PIS1_WONO) WONO,  RTRIM(PIS1_MDLCD) MDLCD,PIS1_BOMRV BOMRV,PIS1_SIMQT SIMQT  FROM XPIS1 A LEFT JOIN XPPSN1 ON A.PIS1_DOCNO=PPSN1_DOCNO
WHERE PPSN1_DOCNO IS NULL AND PIS1_BSGRP LIKE '%PSI1PPZIEP%'
AND PIS1_SIMQT>0
GROUP BY PIS1_WONO, PIS1_DOCNO, PIS1_MDLCD,PIS1_BOMRV,PIS1_SIMQT

GO
/****** Object:  View [dbo].[XPIS0]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XPIS0] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.PIS0_TBL
GO
/****** Object:  View [dbo].[XSIM_CHECKER2]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[XSIM_CHECKER2] AS
SELECT RTRIM(PIS0_DOCNO) DOCNO,RTRIM(PIS1_WONO) WONO, PIS1_MDLCD MDLCD,PIS1_BOMRV BOMRV,PIS1_SIMQT SIMQT FROM XPIS0 A LEFT JOIN XPIS1 ON A.PIS0_DOCNO=PIS1_DOCNO
WHERE PIS1_DOCNO IS NULL AND PIS0_BSGRP LIKE '%PSI1PPZIEP%'
AND PIS1_SIMQT>0
GO
/****** Object:  View [dbo].[XMBSG_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XMBSG_TBL] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.MBSG_TBL
GO
/****** Object:  View [dbo].[XSTRD1]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XSTRD1] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.STRD1_TBL
GO
/****** Object:  View [dbo].[XSTRD2]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XSTRD2] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.STRD2_TBL
GO
/****** Object:  View [dbo].[XSTRD1H]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--select * from XSTRD1 where STRD1_DOCNO like '%OMI%'
CREATE VIEW [dbo].[XSTRD1H] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.STRD1H_TBL 
GO
/****** Object:  View [dbo].[XSTRD2H]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XSTRD2H] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.STRD2H_TBL 
GO
/****** Object:  View [dbo].[XSTKTRND2H]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XSTKTRND2H] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.STKTRND2H_TBL
GO
/****** Object:  View [dbo].[XSTKTRND1H]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XSTKTRND1H] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.STKTRND1H_TBL

--select RTRIM(STRD1_DOCN) STKTRND1_DOCNO,RTRIM(MBSG_BSGRP) MBSG_BSGRP,RTRIM(MBSG_DESC) MBSG_DESC,COUNT(*) TTLITEM,CONVERT(DATE,max(STRD1_ISUDT)) ISUDT, STRD1_LOCCD STKTRND1_LOCCDFR from XSTRD1 INNER JOIN XSTRD2 ON STRD1_RQRLSNO=STRD2_RQRLSNO
--        inner join XMBSG_TBL on STRD1_BSGRP=MBSG_BSGRP
--        GROUP BY STRD1_DOCNO,MBSG_DESC,MBSG_BSGRP,STRD1_LOCCD
GO
/****** Object:  View [dbo].[XSTKTRND2]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XSTKTRND2] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.STKTRND2_TBL 
GO
/****** Object:  View [dbo].[XSTKTRND1]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XSTKTRND1] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.STKTRND1_TBL
GO
/****** Object:  View [dbo].[XVU_RTNX]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO










CREATE VIEW [dbo].[XVU_RTNX] AS
SELECT STKTRND1_DOCNO,MAX(TTLITEM) TTLITEM, ISUDT, STKTRND1_LOCCDFR,RTRIM(MBSG_BSGRP) MBSG_BSGRP,RTRIM(MBSG_DESC) MBSG_DESC FROM
(select RTRIM(STKTRND1_DOCNO) STKTRND1_DOCNO,COUNT(*) TTLITEM,CONVERT(DATE,max(STKTRND1_ISUDT)) ISUDT, rtrim(STKTRND1_LOCCDFR) STKTRND1_LOCCDFR, RTRIM(STKTRND1_BSGRP) STKTRND1_BSGRP from XSTKTRND1 INNER JOIN XSTKTRND2 ON STKTRND1_RQRLSNO=STKTRND2_RQRLSNO        
        GROUP BY STKTRND1_DOCNO,STKTRND1_LOCCDFR, STKTRND1_BSGRP
UNION 
select RTRIM(STRD1_DOCNO) STKTRND1_DOCNO,COUNT(*) TTLITEM,CONVERT(DATE,max(STRD1_ISUDT)) ISUDT, STRD1_LOCCD STKTRND1_LOCCDFR, RTRIM(STRD1_BSGRP) STKTRND1_BSGRP from XSTRD1 INNER JOIN XSTRD2 ON STRD1_RQRLSNO=STRD2_RQRLSNO        
        GROUP BY STRD1_DOCNO,STRD1_LOCCD,STRD1_BSGRP
UNION
select RTRIM(STKTRND1H_DOCNO) STKTRND1_DOCNO,COUNT(*) TTLITEM,CONVERT(DATE,max(STKTRND1H_ISUDT)) ISUDT, STKTRND1H_LOCCDFR STKTRND1_LOCCDFR,RTRIM(STKTRND1H_BSGRP) STKTRND1_BSGRP from XSTKTRND1H INNER join ( SELECT STKTRND1H_DOCNO LTSDOCNO,MAX(STKTRND1H_LUPDT) LTSUPTD FROM XSTKTRND1H GROUP BY STKTRND1H_DOCNO) XSTKTRND1HLTS
		ON XSTKTRND1H.STKTRND1H_LUPDT=LTSUPTD AND XSTKTRND1H.STKTRND1H_DOCNO=LTSDOCNO
		INNER JOIN XSTKTRND2H ON STKTRND1H_RQRLSNO=STKTRND2H_RQRLSNO		
		WHERE substring(STKTRND1H_DOCNO,1,3) NOT IN ('ADJ','TRF')
		GROUP BY STKTRND1H_DOCNO,STKTRND1H_LOCCDFR,STKTRND1H_BSGRP
UNION
select RTRIM(STRD1H_DOCNO) STKTRND1_DOCNO,COUNT(*) TTLITEM,CONVERT(DATE,max(STRD1H_ISUDT)) ISUDT, STRD1H_LOCCD STKTRND1_LOCCDFR, RTRIM(STRD1H_BSGRP) STKTRND1_BSGRP from XSTRD1H INNER JOIN XSTRD2H ON STRD1H_RQRLSNO=STRD2H_RQRLSNO        
        GROUP BY STRD1H_DOCNO,STRD1H_LOCCD,STRD1H_BSGRP

) V1
LEFT JOIN XMBSG_TBL on STKTRND1_BSGRP=MBSG_BSGRP
GROUP BY STKTRND1_DOCNO, ISUDT, STKTRND1_LOCCDFR,RTRIM(MBSG_BSGRP) ,RTRIM(MBSG_DESC) 
GO
/****** Object:  View [dbo].[XVU_RTN_DX]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO












CREATE view [dbo].[XVU_RTN_DX] AS
SELECT V1.*,RTRIM(MBSG_BSGRP) MBSG_BSGRP,RTRIM(MBSG_DESC) MBSG_DESC,RTRIM(MBSG_CURCD) MBSG_CURCD FROM 
(select RTRIM(STKTRND1_DOCNO) STKTRND1_DOCNO,rtrim(STKTRND2_ITMCD) STKTRND2_ITMCD,sum(STKTRND2_TRNQT) STKTRND2_TRNQT,CONVERT(DATE,STKTRND1_ISUDT) ISUDT, STKTRND2_PRICE,STKTRND1_BSGRP,STKTRND2_TRNAM,MAX(STKTRND2_LINE) THELINE from XSTKTRND1 INNER JOIN XSTKTRND2 ON STKTRND1_RQRLSNO=STKTRND2_RQRLSNO        
		group by STKTRND1_DOCNO, STKTRND2_ITMCD, STKTRND1_ISUDT,STKTRND2_PRICE,STKTRND1_BSGRP,STKTRND2_TRNAM
UNION 
select RTRIM(STRD1_DOCNO) STKTRND1_DOCNO,rtrim(STRD2_ITMCD) STKTRND2_ITMCD,sum(STRD2_TRNQT) STKTRND2_TRNQT,CONVERT(DATE,STRD1_ISUDT) ISUDT, STRD2_SLPRC STKTRND2_PRICE,STRD1_BSGRP STKTRND1_BSGRP,STRD2_TRNAM STKTRND2_TRNAM,MAX(STRD2_LINE) THELINE from XSTRD1 INNER JOIN XSTRD2 ON STRD1_RQRLSNO=STRD2_RQRLSNO        
	group by RTRIM(STRD1_DOCNO),rtrim(STRD2_ITMCD) ,CONVERT(DATE,STRD1_ISUDT) ,STRD2_SLPRC,STRD1_BSGRP,STRD2_TRNAM
UNION

SELECT RTRIM(A.STKTRND1H_DOCNO) STKTRND1_DOCNO,rtrim(B.STKTRND2H_ITMCD) STKTRND2_ITMCD,sum(STKTRND2H_TRNQT) STKTRND2_TRNQT,CONVERT(DATE,STKTRND1H_ISUDT) ISUDT,STKTRND2H_PRICE STKTRND2_PRICE, STKTRND1H_BSGRP STKTRND1_BSGRP,STKTRND2H_TRNAM,MAX(STKTRND2H_LINE) THELINE
	FROM XSTKTRND1H A INNER JOIN XSTKTRND2H B ON STKTRND1H_RQRLSNO=STKTRND2H_RQRLSNO 
	RIGHT JOIN (SELECT A.STKTRND1H_DOCNO,STKTRND2H_ITMCD,MAX(STKTRND1H_LUPDT) STKTRND1H_LUPDT 
		FROM XSTKTRND1H A INNER JOIN XSTKTRND2H B ON STKTRND1H_RQRLSNO=STKTRND2H_RQRLSNO 
		RIGHT JOIN (SELECT STKTRND1H_DOCNO,MAX(STKTRND1H_LUPDT) _LUPDT FROM XSTKTRND1H GROUP BY STKTRND1H_DOCNO) _V ON A.STKTRND1H_DOCNO=_V.STKTRND1H_DOCNO AND A.STKTRND1H_LUPDT=_V._LUPDT		
		GROUP BY STKTRND2H_ITMCD,A.STKTRND1H_DOCNO
	) LTS ON A.STKTRND1H_DOCNO=LTS.STKTRND1H_DOCNO AND B.STKTRND2H_ITMCD=LTS.STKTRND2H_ITMCD AND A.STKTRND1H_LUPDT=LTS.STKTRND1H_LUPDT
group by RTRIM(A.STKTRND1H_DOCNO),rtrim(B.STKTRND2H_ITMCD) ,CONVERT(DATE,STKTRND1H_ISUDT) ,STKTRND2H_PRICE,STKTRND1H_BSGRP,STKTRND2H_TRNAM

UNION 
SELECT RTRIM(A.STRD1H_DOCNO) STKTRND1_DOCNO,rtrim(B.STRD2H_ITMCD) STKTRND2_ITMCD,sum(STRD2H_TRNQT) STKTRND2_TRNQT,CONVERT(DATE,STRD1H_ISUDT) ISUDT,STRD2H_SLPRC STKTRND2_PRICE, STRD1H_BSGRP STKTRND1_BSGRP,STRD2H_TRNAM,MAX(STRD2H_LINE) THELINE
	FROM XSTRD1H A INNER JOIN XSTRD2H B ON STRD1H_RQRLSNO=STRD2H_RQRLSNO 
	RIGHT JOIN (SELECT STRD1H_DOCNO,STRD2H_ITMCD,MAX(STRD1H_LUPDT) STKTRND1H_LUPDT FROM XSTRD1H A INNER JOIN XSTRD2H B ON STRD1H_RQRLSNO=STRD2H_RQRLSNO 
GROUP BY STRD2H_ITMCD,STRD1H_DOCNO) LTS ON A.STRD1H_DOCNO=LTS.STRD1H_DOCNO AND B.STRD2H_ITMCD=LTS.STRD2H_ITMCD AND A.STRD1H_LUPDT=LTS.STKTRND1H_LUPDT
group by RTRIM(A.STRD1H_DOCNO),rtrim(B.STRD2H_ITMCD) ,CONVERT(DATE,STRD1H_ISUDT) ,STRD2H_SLPRC,STRD1H_BSGRP,STRD2H_TRNAM
	) V1
LEFT JOIN XMBSG_TBL on STKTRND1_BSGRP=MBSG_BSGRP
GO
/****** Object:  View [dbo].[wms_v_double_unique_tx]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[wms_v_double_unique_tx] as
SELECT VITH.* FROM
	(SELECT ITH_SER,SUM(ITH_QTY) TTLQTY FROM ITH_TBL WHERE ITH_WH IN ('AFWH3','QAFG','ARSHP','NFWH3RT','ARSHPRTN','ARSHPRTN2','AWIP1','AFQART','AFQART2')
	GROUP BY ITH_SER,ITH_WH) VITH
	LEFT JOIN SER_TBL ON ITH_SER=SER_ID
	WHERE TTLQTY>SER_QTY
union all
	SELECT VITH.* FROM
	(SELECT ITH_SER,SUM(ITH_QTY) TTLQTY FROM ITH_TBL WHERE ITH_WH IN ('AFWH3','QAFG','ARSHP','NFWH3RT','ARSHPRTN','ARSHPRTN2','AWIP1','AFQART','AFQART2')
	GROUP BY ITH_SER,ITH_WH) VITH
	LEFT JOIN SER_TBL ON ITH_SER=SER_ID
	WHERE TTLQTY<0
GO
/****** Object:  Table [dbo].[RCV_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RCV_TBL](
	[RCV_PO] [varchar](45) NULL,
	[RCV_INVNO] [varchar](35) NULL,
	[RCV_INVDATE] [date] NULL,
	[RCV_ITMCD] [varchar](50) NOT NULL,
	[RCV_ITMCD_REFF] [varchar](50) NULL,
	[RCV_RPNO] [varchar](30) NULL,
	[RCV_RPDATE] [date] NULL,
	[RCV_BCTYPE] [varchar](15) NULL,
	[RCV_BCNO] [varchar](20) NULL,
	[RCV_BCDATE] [date] NULL,
	[RCV_RCVDATE] [date] NULL,
	[RCV_DONO] [varchar](100) NOT NULL,
	[RCV_DONO_REFF] [varchar](100) NULL,
	[RCV_FRLOCCD] [varchar](50) NULL,
	[RCV_TOLOCCD] [varchar](50) NULL,
	[RCV_QTY] [decimal](12, 3) NULL,
	[RCV_UPRC] [decimal](15, 6) NULL,
	[RCV_AMT] [decimal](16, 6) NULL,
	[RCV_SUPCD] [varchar](50) NOT NULL,
	[RCV_WH] [varchar](50) NULL,
	[RCV_TPB] [char](2) NULL,
	[RCV_PRPRC] [decimal](15, 6) NULL,
	[RCV_TTLAMT] [decimal](16, 6) NULL,
	[RCV_NW] [decimal](12, 3) NULL,
	[RCV_GW] [decimal](12, 3) NULL,
	[RCV_KPPBC] [varchar](10) NULL,
	[RCV_GRLNO] [varchar](50) NULL,
	[RCV_HSCD] [varchar](15) NULL,
	[RCV_ZSTSRCV] [varchar](2) NULL,
	[RCV_BM] [decimal](18, 1) NULL,
	[RCV_PPN] [decimal](18, 1) NULL,
	[RCV_PPH] [decimal](18, 1) NULL,
	[RCV_ZNOURUT] [varchar](4) NULL,
	[RCV_BSGRP] [varchar](15) NULL,
	[RCV_CONA] [varchar](50) NULL,
	[RCV_ITMD1] [varchar](100) NULL,
	[RCV_SPTNO] [varchar](100) NULL,
	[RCV_DUEDT] [date] NULL,
	[RCV_CONADT] [date] NULL,
	[RCV_INVNOACT] [varchar](50) NULL,
	[RCV_PKGNUM] [varchar](40) NULL,
	[RCV_TAXINVOICE] [varchar](25) NULL,
	[RCV_PRNW] [decimal](14, 5) NULL,
	[RCV_PRGW] [decimal](14, 5) NULL,
	[RCV_ASSETNUM] [varchar](50) NULL,
	[RCV_LUPDT] [datetime] NOT NULL,
	[RCV_USRID] [varchar](50) NOT NULL,
	[RCV_CREATEDBY] [varchar](20) NULL,
	[RCV_SHIPPERCD] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[ZRPSAL_BCSTOCK]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[ZRPSAL_BCSTOCK] AS
SELECT *, CASE WHEN RPSTOCK_BCDATEOUT IS NULL THEN RPSTOCK_BCDATE ELSE RPSTOCK_BCDATEOUT END  IODATE FROM PSI_RPCUST.dbo.RPSAL_BCSTOCK where RPSAL_BCSTOCK.deleted_at is null
GO
/****** Object:  View [dbo].[VBALEXBC]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[VBALEXBC]
AS
SELECT VSTOCKBC.*
	,CONVERT(VARCHAR(20), RCV_PRPRC) PRICE,RCV_BCDATE,RTRIM(MITM_ITMD1) MITM_ITMD1,RCV_CONA,RCV_BCTYPE,RCV_WH,MSUP_SUPNM,RTRIM(MITM_SPTNO) MITM_SPTNO
FROM (
	SELECT RTRIM(RPSTOCK_ITMNUM) ITMNUM
		,RPSTOCK_NOAJU
		,RPSTOCK_BCNUM
		,sum(RPSTOCK_QTY) STK
		,RPSTOCK_DOC
	FROM ZRPSAL_BCSTOCK
	WHERE RPSTOCK_DOC NOT LIKE '%-RNK'
	GROUP BY RPSTOCK_ITMNUM
		,RPSTOCK_NOAJU
		,RPSTOCK_BCNUM
		,RPSTOCK_DOC
	HAVING sum(RPSTOCK_QTY) > 0
	) VSTOCKBC
LEFT JOIN (
	SELECT RCV_ITMCD
		,RCV_RPNO
		,RCV_BCNO
		,RCV_PRPRC
		,RCV_DONO
		,RCV_BCDATE
		,RCV_CONA
		,MAX(RCV_BCTYPE) RCV_BCTYPE
		,MAX(RTRIM(RCV_WH)) RCV_WH
		,MAX(RCV_SUPCD) RCV_SUPCD
	FROM RCV_TBL
	GROUP BY RCV_ITMCD
		,RCV_RPNO
		,RCV_BCNO
		,RCV_PRPRC
		,RCV_DONO
		,RCV_BCDATE
		,RCV_CONA
	) VRCV ON VSTOCKBC.ITMNUM = RCV_ITMCD
	AND RPSTOCK_NOAJU = RCV_RPNO
	AND RPSTOCK_BCNUM = RCV_BCNO
	AND RPSTOCK_DOC = RCV_DONO
LEFT JOIN MITM_TBL ON RCV_ITMCD=MITM_ITMCD
LEFT JOIN (SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM FROM MSUP_TBL GROUP BY MSUP_SUPCD) VSUP ON RCV_SUPCD=MSUP_SUPCD
GO
/****** Object:  View [dbo].[XMITM_V]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[XMITM_V] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.MITM_TBL 
GO
/****** Object:  View [dbo].[XPWOP]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XPWOP] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.PWOP_TBL
GO
/****** Object:  View [dbo].[VSUBASSY]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[VSUBASSY] AS
SELECT RTRIM(PWOP_MDLCD) PWOP_MDLCD
    FROM XPWOP
    INNER JOIN XMITM_V
        ON PWOP_BOMPN=MITM_ITMCD
    WHERE MITM_MODEL='1'
    GROUP BY  PWOP_MDLCD
GO
/****** Object:  View [dbo].[VFG_AS_BOM]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[VFG_AS_BOM] AS
SELECT PWOP_BOMPN
    FROM XPWOP
    INNER JOIN XMITM_V
        ON PWOP_BOMPN=MITM_ITMCD
    WHERE MITM_MODEL='1'
    GROUP BY  PWOP_BOMPN
GO
/****** Object:  Table [dbo].[SI_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SI_TBL](
	[SI_DOC] [varchar](50) NOT NULL,
	[SI_DOCREFF] [varchar](50) NULL,
	[SI_DOCREFFDT] [datetime] NULL,
	[SI_DOCREFFQTY] [decimal](12, 3) NULL,
	[SI_DOCREFFPRC] [decimal](15, 6) NULL,
	[SI_DOCREFFETA] [datetime] NULL,
	[SI_TRANSITDT] [datetime] NULL,
	[SI_DLVDT] [date] NULL,
	[SI_CUSCD] [varchar](50) NULL,
	[SI_ITMCD] [varchar](50) NOT NULL,
	[SI_MDL] [varchar](50) NULL,
	[SI_QTY] [decimal](12, 3) NULL,
	[SI_QTYACT] [decimal](12, 3) NULL,
	[SI_REQDT] [datetime] NOT NULL,
	[SI_OTHRMRK] [varchar](50) NULL,
	[SI_HRMRK] [varchar](50) NULL,
	[SI_RMRK] [varchar](50) NULL,
	[SI_LINENO] [varchar](25) NULL,
	[SI_PURORG] [varchar](50) NULL,
	[SI_DESCR] [varchar](50) NULL,
	[SI_FRUSER] [varchar](50) NULL,
	[SI_FIFOPO] [varchar](50) NULL,
	[SI_BSGRP] [varchar](15) NULL,
	[SI_LINE] [int] NULL,
	[SI_WH] [varchar](20) NULL,
	[SI_LUPDT] [datetime] NULL,
	[SI_USRID] [varchar](50) NULL,
	[SI_CREATEDAT] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[v_bg_cust_si]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_bg_cust_si] as 
select SI_BSGRP,SI_CUSCD from SI_TBL GROUP BY SI_BSGRP, SI_CUSCD
GO
/****** Object:  Table [dbo].[SISO_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SISO_TBL](
	[SISO_HLINE] [varchar](40) NOT NULL,
	[SISO_CPONO] [varchar](100) NOT NULL,
	[SISO_SOLINE] [varchar](50) NOT NULL,
	[SISO_FLINE] [varchar](40) NOT NULL,
	[SISO_LINE] [int] NULL,
	[SISO_QTY] [int] NULL,
	[SISO_NOTES] [varchar](7) NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[VSISO_PRICE]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE VIEW [dbo].[VSISO_PRICE] AS
SELECT SISO_HLINE,MIN(SSO2_SLPRC) SISOPRC,MAX(SISO_SOLINE) SISO_SOLINE  FROM SISO_TBL LEFT JOIN XSSO2 ON SISO_CPONO=SSO2_CPONO AND SISO_SOLINE=SSO2_SOLNO
GROUP BY SISO_HLINE
GO
/****** Object:  View [dbo].[vr_vis_fg_NFWH4RT]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--SELECT * FROM MSTLOCG_TBL


CREATE VIEW [dbo].[vr_vis_fg_NFWH4RT] as
select x1.* from
(SELECT  v1.*,ITH_LOC,SER_ITMID,isnull(ITH_LINE,'') ITH_LINE from
(select ITH_WH,ITH_SER,SUM(ITH_QTY) ITH_QTY, MAX(ITH_LUPDT) LTS_TIME from ITH_TBL WHERE ITH_WH='NFWH4RT' 
GROUP BY ITH_WH,ITH_SER
HAVING SUM(ITH_QTY) >0) v1
inner join ITH_TBL a ON v1.ITH_SER=a.ITH_SER and a.ITH_WH=v1.ITH_WH and isnull(v1.LTS_TIME,GETDATE())=isnull(a.ITH_LUPDT,GETDATE()) 
left JOIN SER_TBL ON a.ITH_SER=SER_ID) x1
left JOIN 
(
select ITH_SER,isnull(MAX(ITH_LINE),'') VNX_MAXLINE from(
SELECT  v1.*,ITH_LOC,SER_ITMID,ITH_LINE from
(select ITH_WH,ITH_SER,SUM(ITH_QTY) ITH_QTY, MAX(ITH_LUPDT) LTS_TIME from ITH_TBL WHERE ITH_WH='NFWH4RT' 
GROUP BY ITH_WH,ITH_SER
HAVING SUM(ITH_QTY) >0) v1
inner join ITH_TBL a ON v1.ITH_SER=a.ITH_SER and a.ITH_WH=v1.ITH_WH and isnull(v1.LTS_TIME,GETDATE())=isnull(a.ITH_LUPDT,GETDATE()) 
left JOIN SER_TBL ON a.ITH_SER=SER_ID) vn
group by ITH_SER) x2 on x1.ITH_SER=x2.ITH_SER and x1.ITH_LINE=x2.VNX_MAXLINE
GO
/****** Object:  View [dbo].[vr_vis_fg_AFWH3RT]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[vr_vis_fg_AFWH3RT] as
select x1.* from
(SELECT  v1.*,ITH_LOC,SER_ITMID,isnull(ITH_LINE,'') ITH_LINE from
(select ITH_WH,ITH_SER,SUM(ITH_QTY) ITH_QTY, MAX(ITH_LUPDT) LTS_TIME from ITH_TBL WHERE ITH_WH='AFWH3RT' 
GROUP BY ITH_WH,ITH_SER
HAVING SUM(ITH_QTY) >0) v1
inner join ITH_TBL a ON v1.ITH_SER=a.ITH_SER and a.ITH_WH=v1.ITH_WH and isnull(v1.LTS_TIME,GETDATE())=isnull(a.ITH_LUPDT,GETDATE()) 
left JOIN SER_TBL ON a.ITH_SER=SER_ID) x1
left JOIN 
(
select ITH_SER,isnull(MAX(ITH_LINE),'') VNX_MAXLINE from(
SELECT  v1.*,ITH_LOC,SER_ITMID,ITH_LINE from
(select ITH_WH,ITH_SER,SUM(ITH_QTY) ITH_QTY, MAX(ITH_LUPDT) LTS_TIME from ITH_TBL WHERE ITH_WH='AFWH3RT' 
GROUP BY ITH_WH,ITH_SER
HAVING SUM(ITH_QTY) >0) v1
inner join ITH_TBL a ON v1.ITH_SER=a.ITH_SER and a.ITH_WH=v1.ITH_WH and isnull(v1.LTS_TIME,GETDATE())=isnull(a.ITH_LUPDT,GETDATE()) 
left JOIN SER_TBL ON a.ITH_SER=SER_ID) vn
group by ITH_SER) x2 on x1.ITH_SER=x2.ITH_SER and x1.ITH_LINE=x2.VNX_MAXLINE
GO
/****** Object:  Table [dbo].[ITMLOC_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[ITMLOC_TBL](
	[ITMLOC_ITM] [varchar](50) NOT NULL,
	[ITMLOC_LOCG] [varchar](50) NOT NULL,
	[ITMLOC_LOC] [varchar](50) NOT NULL,
	[ITMLOC_BG] [varchar](25) NULL,
	[ITMLOC_LUPDT] [datetime] NULL,
	[ITMLOC_USRID] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[wms_v_unregistered_item_in_xray]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[wms_v_unregistered_item_in_xray]
as
select RTRIM(MITM_ITMCD) MITM_ITMCD,MAX(ITMLOC_LOC) ITMLOC_LOC,RTRIM(MITM_SPTNO) MITM_SPTNO from MITM_TBL LEFT JOIN openquery(SRVXRAY, 'select * from items')
ON item_code=MITM_ITMCD
left join ITMLOC_TBL on MITM_ITMCD=ITMLOC_ITM
WHERE item_code is null and MITM_MODEL='0'
AND ITMLOC_LOC IS NOT NULL
GROUP BY MITM_ITMCD,MITM_SPTNO
GO
/****** Object:  View [dbo].[v_delivery_rm_unconfirmed]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO






CREATE VIEW [dbo].[v_delivery_rm_unconfirmed]
as
SELECT DLV_ID, '' PLANT,DLV_BCDATE, '' CUSTDO ,CONCAT(MAX(DLV_RMRK),' (RPR)') DLV_RMRK FROM
(select DLV_ITMCD,DLV_ID,sum(DLV_QTY) DLV_QTY,MAX(DLV_RMRK) DLV_RMRK,MAX(DLV_BCDATE) DLV_BCDATE from DLV_TBL where  DLV_SER=''
group by DLV_ITMCD,DLV_ID) VDELV
LEFT JOIN 
(
SELECT ITH_ITMCD,ITH_DOC FROM ITH_TBL WHERE ITH_WH IN ('ARWH0PD','PSIEQUIP','NRWH2','AIWH1') AND ITH_FORM='OUT-SHP-RM'
) VITH ON VDELV.DLV_ID=VITH.ITH_DOC AND VDELV.DLV_ITMCD=VITH.ITH_ITMCD
WHERE ITH_DOC IS NULL AND DLV_ITMCD NOT LIKE 'SCRAP%'
GROUP BY DLV_ID,DLV_BCDATE

GO
/****** Object:  View [dbo].[XMSUP]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XMSUP] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.MSUP_TBL
GO
/****** Object:  View [dbo].[XPGRN_VIEW]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[XPGRN_VIEW]
AS
SELECT        a.PGRN_LOCCD, RTRIM(a.PGRN_BSGRP) PGRN_BSGRP, a.PGRN_SUPCD, a.PGRN_SUPCR, a.PGRN_SUPNO, a.PGRN_ITMCD, a.PGRN_GRLNO, a.PGRN_GRNTY, a.PGRN_PONO, a.PGRN_POLNO, a.PGRN_CUSCD,  a.PGRN_CURCD, a.PGRN_SPART, 
                         a.PGRN_ICMQT, a.PGRN_RCVDT, a.PGRN_LOTNO, a.PGRN_RCVQT, a.PGRN_ROKQT, a.PGRN_RNGQT, a.PGRN_UPDBL, a.PGRN_TMOBL, a.PGRN_TMCBL, a.PGRN_LOCPC, a.PGRN_PRPRC, a.PGRN_AMT, a.PGRN_XRATE, 
                         a.PGRN_OWNER, a.PGRN_CONFG, a.PGRN_USRID, a.PGRN_LUPDT, a.PGRN_LOCAM, a.PGRN_RQRLSNO, a.PGRN_PRGID, a.PGRN_POUOM, a.PGRN_ICMPOUOMQTY, a.PGRN_RCVPOUOMQTY, a.PGRN_ROKPOUOMQTY, 
                         a.PGRN_RNGPOUOMQTY, a.PGRN_POUOMPRC, vc.MSUP_SUPNM,MSUP_SUPCR
FROM            SRVMEGA.PSI_MEGAEMS.dbo.PGRN_TBL AS a LEFT JOIN
(
select MSUP_SUPCD,max(MSUP_SUPNM) MSUP_SUPNM,MAX(RTRIM(MSUP_SUPCR)) MSUP_SUPCR from
(select MSUP_SUPCD, MSUP_SUPNM,MSUP_SUPCR  from XMSUP
union
select MCUS_CUSCD MSUP_SUPCD,MCUS_CUSNM MSUP_SUPNM, RTRIM(MCUS_CURCD) MSUP_SUPCR from XMCUS) vb
group by MSUP_SUPCD
) vc
                           ON a.PGRN_SUPCD = vc.MSUP_SUPCD
--SRVMEGA.PSI_MEGAEMS.dbo.MSUP_TBL AS

GO
/****** Object:  View [dbo].[XPPO1]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XPPO1] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.PPO1_TBL
GO
/****** Object:  View [dbo].[XPPO2]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XPPO2] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.PPO2_TBL
GO
/****** Object:  View [dbo].[v_mega_po]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


--select * from XPPO1 where PPO1_PONO='22040207'
--select top 200 * from XPPO2 where PPO2_ITMCD='100000000' and PPO2_PONO='22040207'
--select * from XPGRN_VIEW where PGRN_SUPNO='0690042022SMT'

CREATE VIEW [dbo].[v_mega_po] as
SELECT RTRIM(PPO2_PONO) PO_NO 
,PPO2_REVNO PO_REV
,PPO1_REPLYDUEDT PO_REQDT
,PPO2_ISUDT PO_ISSUDT
,PPO2_SUPCD PO_SUPCD
,RTRIM(PPO2_ITMCD) PO_ITMCD
,PPO2_POQTY PO_QTY
,PPO1_LUPDT PO_CRTDT
,NULL PO_CRTBY
,NULL PO_STS
,0 PO_PPH
,0 PO_VAT
,PPO1_LUPDT PO_LUPDTD
,NULL PO_LUPDTDBY
,NULL PO_APPRVBY
,NULL PO_APPRVDT
,PPO2_POLNO PO_LINE
,'' PO_SUBJECT
,'' PO_DEPT
,'' PO_SECTION
,'' PO_SHPDLV
,0 PO_DISC
,'' PO_RQSTBY
,PPO1_PTERM PO_PAYTERM
,'' PO_RMRK
,NULL PO_ITMNM
,NULL PO_UM
,NULL PO_ITMTYPE
,0 PO_SHPCOST
,PPO2_PRPRC PO_PRICE
,MITM_ITMD1
,MITM_STKUOM
,MSUP_SUPNM
,MSUP_SUPCR
,MSUP_ADDR1
,MSUP_TELNO
,MSUP_FAXNO
,PPO2_GRNQT RCVQTY
,0 SPECIALDISC
FROM XPPO2
LEFT JOIN XPPO1 ON PPO2_PONO=PPO1_PONO
LEFT JOIN XMITM_V ON PPO2_ITMCD=MITM_ITMCD
LEFT JOIN XMSUP ON PPO2_SUPCD=MSUP_SUPCD
GO
/****** Object:  View [dbo].[VNPSI_USERS]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

--EXEC sp_addlinkedserver @server = 'PSI-SVR4'
CREATE view [dbo].[VNPSI_USERS] AS
select * from Employees.dbo.users
GO
/****** Object:  View [dbo].[VPSI_USERS_UNREGISTERED]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


--select * from VNPSI_USERS where user_nicename like '%INDRI YANI%'

CREATE VIEW [dbo].[VPSI_USERS_UNREGISTERED] AS
select ID, RTRIM(LTRIM(user_nicename)) user_nicename,user_dept,user_doj from VNPSI_USERS 
left join MSTEMP_TBL on ID=MSTEMP_ID COLLATE SQL_Latin1_General_CP1_CI_AS
WHERE MSTEMP_ID IS NULL 



GO
/****** Object:  View [dbo].[v_ith_tblc]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- dbo.v_ith_tblc source

CREATE VIEW [dbo].[v_ith_tblc] as
SELECT *
	, CASE WHEN convert(varchar(10), ITH_LUPDT, 108) < '07:00:00'  THEN DATEADD(DAY,-1,ITH_DATE) ELSE convert(date,ITH_LUPDT) END ITH_DATEC
	FROM ITH_TBL WITH (NOLOCK);
GO
/****** Object:  View [dbo].[V_STOCK_FG_AFWH3]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[V_STOCK_FG_AFWH3] AS
select ITH_ITMCD,SUM(ITH_QTY) STOCKQTY from v_ith_tblc
where ITH_WH='AFWH3'
GROUP BY ITH_ITMCD
GO
/****** Object:  Table [dbo].[MSTLOCG_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MSTLOCG_TBL](
	[MSTLOCG_ID] [varchar](50) NOT NULL,
	[MSTLOCG_NM] [varchar](50) NULL,
	[MSTLOCG_DEDICATED] [varchar](50) NULL,
	[MSTLOCG_LUPDT] [datetime] NULL,
	[MSTLOCG_USRID] [varchar](50) NULL,
 CONSTRAINT [PK_MSTLOCG_TBL] PRIMARY KEY CLUSTERED 
(
	[MSTLOCG_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[v_locltsid]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[v_locltsid] as
select COALESCE(max(cast(SUBSTRING(MSTLOCG_ID,4,11) as bigint)),'0') lastid from MSTLOCG_TBL
GO
/****** Object:  View [dbo].[V_STOCK_FG_AFWH3RT]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[V_STOCK_FG_AFWH3RT] AS
select ITH_ITMCD,SUM(ITH_QTY) STOCKQTY from v_ith_tblc
where ITH_WH='AFWH3RT'
GROUP BY ITH_ITMCD
GO
/****** Object:  View [dbo].[XVU_RTN]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE VIEW [dbo].[XVU_RTN] AS
SELECT XVU_RTNX.* FROM XVU_RTNX
inner JOIN (SELECT STKTRND1_DOCNO, MAX(ISUDT) ISUDTLTS FROM XVU_RTNX GROUP BY STKTRND1_DOCNO) XVU_RTNLTS
ON XVU_RTNX.STKTRND1_DOCNO=XVU_RTNLTS.STKTRND1_DOCNO AND ISUDT=ISUDTLTS
GO
/****** Object:  Table [dbo].[MSTLOC_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MSTLOC_TBL](
	[MSTLOC_GRP] [varchar](50) NOT NULL,
	[MSTLOC_CD] [varchar](50) NOT NULL,
	[MSTLOC_RACK] [varchar](50) NULL,
	[MSTLOC_NO] [varchar](50) NULL,
	[MSTLOC_CAT] [varchar](50) NULL,
	[MSTLOC_ORDER] [numeric](6, 5) NULL,
	[MSTLOC_LUPDT] [datetime] NULL,
	[MSTLOC_USRID] [varchar](50) NULL,
 CONSTRAINT [PK__MSTLOC_T__B8EF687047C4B461] PRIMARY KEY CLUSTERED 
(
	[MSTLOC_GRP] ASC,
	[MSTLOC_CD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[v_locdltsid]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[v_locdltsid] as
select COALESCE(max(cast(SUBSTRING(MSTLOC_ID,4,11) as bigint)),'0') lastid from MSTLOC_TBL
GO
/****** Object:  View [dbo].[V_STOCK_FG_NFWH4RT]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[V_STOCK_FG_NFWH4RT] AS
select ITH_ITMCD,SUM(ITH_QTY) STOCKQTY from v_ith_tblc
where ITH_WH='NFWH4RT'
GROUP BY ITH_ITMCD
GO
/****** Object:  View [dbo].[XVU_RTN_D]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


--CREATE VIEW XVU_RTN_SUB AS
--SELECT XVU_RTN.* FROM XVU_RTN
--INNER JOIN (SELECT STKTRND1_DOCNO, MAX(ISUDT) ISUDTLTS FROM XVU_RTN GROUP BY STKTRND1_DOCNO) XVU_RTNLTS
--ON XVU_RTN.STKTRND1_DOCNO=XVU_RTNLTS.STKTRND1_DOCNO AND ISUDT=ISUDTLTS

CREATE VIEW [dbo].[XVU_RTN_D] AS
SELECT XVU_RTN_DX.* FROM XVU_RTN_DX 
INNER JOIN (SELECT STKTRND1_DOCNO, MAX(ISUDT) ISUDTLTS FROM XVU_RTN_DX GROUP BY STKTRND1_DOCNO) XVU_RTN_DLTS
ON XVU_RTN_DX.STKTRND1_DOCNO=XVU_RTN_DLTS.STKTRND1_DOCNO AND ISUDT=ISUDTLTS

GO
/****** Object:  View [dbo].[XWO]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XWO] AS
select RTRIM(PDPP_WONO) PDPP_WONO, RTRIM(MBOM_GRADE) MBOM_GRADE,PDPP_WORQT,PDPP_GRNQT,RTRIM(PDPP_MDLCD) PDPP_MDLCD,PDPP_CUSCD,PDPP_BSGRP,PDPP_COMFG,PDPP_ISUDT,PDPP_BOMRV,PDPP_REQDT from
(SELECT MBOM_MDLCD,MBOM_BOMRV,MBOM_GRADE FROM SRVMEGA.PSI_MEGAEMS.dbo.MBOM_TBL
GROUP BY MBOM_MDLCD,MBOM_BOMRV,MBOM_GRADE) v1
INNER JOIN SRVMEGA.PSI_MEGAEMS.dbo.PDPP_TBL
ON MBOM_MDLCD=PDPP_MDLCD  AND MBOM_BOMRV=PDPP_BOMRV
GO
/****** Object:  View [dbo].[vqc_unconform]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE VIEW [dbo].[vqc_unconform] as
SELECT SER_ID,SER_ITMID,MITM_ITMD1,SER_QTY,SER_LOTNO,SER_DOC,SER_LUPDT, PDPP_BSGRP,CONCAT(MSTEMP_FNM,' ',MSTEMP_LNM) PIC FROM SER_TBL left join 
(Select ITH_SER from ITH_TBL GROUP BY ITH_SER) v1
on SER_ID=ITH_SER
inner join XWO ON SER_DOC=PDPP_WONO
INNER JOIN MITM_TBL ON SER_ITMID=MITM_ITMCD
INNER JOIN MSTEMP_TBL ON SER_USRID=MSTEMP_ID
WHERE 
 SER_USRID !='ane' AND ITH_SER IS NULL AND SER_QTY>0
 UNION
 --SPLIT IN PRODUCTION LIST
 SELECT SER_ID,SER_ITMID,MITM_ITMD1,SER_QTY,SER_LOTNO,SER_DOC,SER_LUPDT,PDPP_BSGRP, MSTEMP_FNM PIC FROM 
(SELECT ITH_SER,SUM(ITH_QTY) STKQTY FROM ITH_TBL WHERE ITH_WH='ARPRD1'
GROUP BY ITH_SER
HAVING SUM(ITH_QTY)>0) V1
INNER JOIN 
(SELECT DISTINCT ITH_SER FROM ITH_TBL WHERE ITH_WH='ARPRD1' AND ITH_FORM='SPLIT-FG-LBL') VSPLITPRD ON V1.ITH_SER=VSPLITPRD.ITH_SER
INNER JOIN SER_TBL ON VSPLITPRD.ITH_SER=SER_ID
LEFT JOIN MITM_TBL ON SER_ITMID=MITM_ITMCD
INNER JOIN XWO ON SER_DOC=PDPP_WONO
LEFT JOIN MSTEMP_TBL ON SER_USRID=MSTEMP_ID
GO
/****** Object:  View [dbo].[XPSN_VIEW]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XPSN_VIEW]
AS
SELECT        PPSN1_TBL_1.PPSN1_PSNNO, PPSN2_TBL_1.PPSN2_ITMCAT, PPSN1_TBL_1.PPSN1_LINENO, PPSN1_TBL_1.PPSN1_FR, PPSN1_TBL_1.PPSN1_WONO, PPSN1_TBL_1.PPSN1_MDLCD, PPSN2_TBL_1.PPSN2_MCZ, 
                         PPSN2_TBL_1.PPSN2_QTPER, PPSN2_TBL_1.PPSN2_REQQT, PPSN2_TBL_1.PPSN2_MSFLG, PPSN2_TBL_1.PPSN2_SUBPN, PPSN2_TBL_1.PPSN2_PROCD, PPSN1_TBL_1.PPSN1_LUPDT
FROM            SRVMEGA.PSI_MEGAEMS.dbo.PPSN1_TBL AS PPSN1_TBL_1 INNER JOIN
                         SRVMEGA.PSI_MEGAEMS.dbo.PPSN2_TBL AS PPSN2_TBL_1 ON PPSN1_TBL_1.PPSN1_BSGRP = PPSN2_TBL_1.PPSN2_BSGRP AND PPSN1_TBL_1.PPSN1_DOCNO = PPSN2_TBL_1.PPSN2_DOCNO AND 
                         PPSN1_TBL_1.PPSN1_PSNNO = PPSN2_TBL_1.PPSN2_PSNNO AND PPSN1_TBL_1.PPSN1_LINENO = PPSN2_TBL_1.PPSN2_LINENO AND PPSN1_TBL_1.PPSN1_FR = PPSN2_TBL_1.PPSN2_FR
GO
/****** Object:  Table [dbo].[SPL_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SPL_TBL](
	[SPL_DOC] [varchar](50) NOT NULL,
	[SPL_DOCNO] [varchar](50) NULL,
	[SPL_CAT] [varchar](50) NOT NULL,
	[SPL_LINE] [varchar](50) NOT NULL,
	[SPL_FEDR] [varchar](50) NOT NULL,
	[SPL_PROCD] [varchar](20) NULL,
	[SPL_RMRK] [varchar](50) NULL,
	[SPL_BG] [varchar](50) NULL,
	[SPL_MC] [varchar](40) NULL,
	[SPL_ORDERNO] [varchar](50) NOT NULL,
	[SPL_RACKNO] [varchar](50) NULL,
	[SPL_ITMCD] [varchar](50) NOT NULL,
	[SPL_QTYUSE] [decimal](12, 3) NULL,
	[SPL_QTYREQ] [decimal](12, 3) NULL,
	[SPL_MS] [char](1) NULL,
	[SPL_USRGRP] [varchar](50) NULL,
	[SPL_FMDL] [varchar](50) NULL,
	[SPL_JOBNO_REC_RM] [varchar](1) NULL,
	[SPL_LUPDT] [datetime] NULL,
	[SPL_USRID] [varchar](9) NULL,
	[SPL_REFDOCCAT] [varchar](50) NULL,
	[SPL_REFDOCNO] [varchar](50) NULL,
	[SPL_LINEDATA] [int] NULL,
	[SPL_PRNKIDT] [datetime] NULL,
	[SPL_ITMRMRK] [varchar](150) NULL,
	[SPL_APPRV_BY] [varchar](9) NULL,
	[SPL_APPRV_TM] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[created_at] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[SPL_VIEW]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[SPL_VIEW]
AS
SELECT        dbo.XPSN_VIEW.PPSN1_PSNNO, dbo.XPSN_VIEW.PPSN2_ITMCAT, dbo.XPSN_VIEW.PPSN1_LINENO, dbo.XPSN_VIEW.PPSN1_FR, dbo.XPSN_VIEW.PPSN1_WONO, dbo.XPSN_VIEW.PPSN1_MDLCD, 
                         dbo.XPSN_VIEW.PPSN2_MCZ, dbo.XPSN_VIEW.PPSN2_QTPER, dbo.XPSN_VIEW.PPSN2_REQQT, dbo.XPSN_VIEW.PPSN2_MSFLG, dbo.XPSN_VIEW.PPSN2_PROCD, dbo.XPSN_VIEW.PPSN1_LUPDT, 
                         dbo.XPSN_VIEW.PPSN2_SUBPN
FROM            dbo.XPSN_VIEW LEFT OUTER JOIN
                         dbo.SPL_TBL ON dbo.XPSN_VIEW.PPSN2_MSFLG = dbo.SPL_TBL.SPL_MS AND dbo.XPSN_VIEW.PPSN2_MCZ = dbo.SPL_TBL.SPL_ORDERNO AND dbo.XPSN_VIEW.PPSN1_MDLCD = dbo.SPL_TBL.SPL_FG AND 
                         dbo.XPSN_VIEW.PPSN1_WONO = dbo.SPL_TBL.SPL_JOBNO AND dbo.XPSN_VIEW.PPSN1_FR = dbo.SPL_TBL.SPL_FEDR AND dbo.XPSN_VIEW.PPSN1_LINENO = dbo.SPL_TBL.SPL_LINE AND 
                         dbo.XPSN_VIEW.PPSN2_ITMCAT = dbo.SPL_TBL.SPL_CAT AND dbo.SPL_TBL.SPL_DOC = dbo.XPSN_VIEW.PPSN1_PSNNO
WHERE        (dbo.SPL_TBL.SPL_DOC IS NULL) AND (dbo.SPL_TBL.SPL_CAT IS NULL) AND (dbo.SPL_TBL.SPL_LINE IS NULL) AND (dbo.SPL_TBL.SPL_FEDR IS NULL) AND (dbo.SPL_TBL.SPL_JOBNO IS NULL) AND 
                         (dbo.SPL_TBL.SPL_FG IS NULL) AND (dbo.SPL_TBL.SPL_ORDERNO IS NULL) AND (dbo.SPL_TBL.SPL_MS IS NULL)
GO
/****** Object:  View [dbo].[VWMSPSI_USERS]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[VWMSPSI_USERS] AS
SELECT        MSTEMP_TBL.MSTEMP_ID, MSTEMP_TBL.MSTEMP_FNM, MSTEMP_TBL.MSTEMP_LNM, MSTEMP_TBL.MSTEMP_NIK, MSTEMP_TBL.MSTEMP_PW, MSTEMP_TBL.MSTEMP_GRP, MSTEMP_TBL.MSTEMP_ACTSTS, 
                         MSTEMP_TBL.MSTEMP_STS, MSTEMP_TBL.MSTEMP_DACTTM, MSTEMP_TBL.MSTEMP_REGTM, MSTEMP_TBL.MSTEMP_PWHT, MSTEMP_TBL.MSTEMP_IP, MSTEMP_TBL.MSTEMP_ACTTM, MSTEMP_TBL.updated_at, 
                         ISNULL(VNPSI_USERS.user_dept,'') PSIDEPT
FROM            MSTEMP_TBL LEFT OUTER JOIN
                         VNPSI_USERS ON MSTEMP_TBL.MSTEMP_ID = VNPSI_USERS.ID COLLATE SQL_Latin1_General_CP1_CI_AS
GO
/****** Object:  Table [dbo].[PO_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PO_TBL](
	[PO_NO] [varchar](12) NOT NULL,
	[PO_REV] [char](1) NOT NULL,
	[PO_REQDT] [date] NULL,
	[PO_ISSUDT] [date] NULL,
	[PO_SUPCD] [varchar](15) NULL,
	[PO_ITMCD] [varchar](90) NULL,
	[PO_QTY] [decimal](12, 3) NULL,
	[PO_CRTDT] [datetime] NULL,
	[PO_CRTBY] [varchar](20) NULL,
	[PO_STS] [char](1) NULL,
	[PO_PPH] [decimal](15, 2) NULL,
	[PO_VAT] [decimal](15, 2) NULL,
	[PO_LUPDTD] [datetime] NULL,
	[PO_LUPDTDBY] [varchar](50) NULL,
	[PO_APPRVBY] [varchar](20) NULL,
	[PO_APPRVDT] [datetime] NULL,
	[PO_LINE] [int] NOT NULL,
	[PO_SUBJECT] [varchar](10) NULL,
	[PO_DEPT] [varchar](10) NULL,
	[PO_SECTION] [varchar](45) NULL,
	[PO_SHPDLV] [varchar](50) NULL,
	[PO_DISC] [decimal](10, 3) NULL,
	[PO_RQSTBY] [varchar](50) NULL,
	[PO_PAYTERM] [varchar](100) NULL,
	[PO_RMRK] [varchar](100) NULL,
	[PO_ITMNM] [varchar](150) NULL,
	[PO_UM] [varchar](15) NULL,
	[PO_ITMTYPE] [varchar](50) NULL,
	[PO_SHPCOST] [decimal](12, 3) NULL,
	[PO_PRICE] [decimal](18, 6) NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[VPO_RESUME]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[VPO_RESUME] AS
SELECT PO_NO,PO_SUPCD,PO_ITMNM,SUM(PO_QTY) PO_QTY,PO_ITMCD,PO_UM,PO_REQDT,PO_PRICE,PO_ISSUDT FROM PO_TBL 
        GROUP BY PO_NO,PO_SUPCD,PO_ITMNM,PO_ITMCD,PO_UM,PO_REQDT,PO_PRICE,PO_ISSUDT
GO
/****** Object:  View [dbo].[wms_v_fg_slow_moving]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[wms_v_fg_slow_moving] as
SELECT * FROM
(
SELECT VSTK.*,LTSDELV
,DATEDIFF(DAY,CASE WHEN LTSDELV IS NULL THEN LTSDT ELSE LTSDELV END,GETDATE()) DAYLEFT ,LTSINC
,DATEDIFF(DAY,LTSINC,GETDATE()) DAYLEFTINC
FROM 
	(select ITH_ITMCD,SUM(ITH_QTY) STKQTY,MAX(ITH_LUPDT) LTSDT from ITH_TBL where ITH_WH='AFWH3'
	GROUP BY ITH_ITMCD
	HAVING SUM(ITH_QTY)>0) VSTK
LEFT JOIN (
	SELECT ITH_ITMCD,MAX(ITH_LUPDT) LTSDELV FROM ITH_TBL WHERE ITH_WH='ARSHP'
	GROUP BY ITH_ITMCD
	) VSEND ON VSTK.ITH_ITMCD=VSEND.ITH_ITMCD
LEFT JOIN (
	SELECT ITH_ITMCD,MAX(ITH_LUPDT) LTSINC FROM ITH_TBL WHERE ITH_WH='AFWH3' AND ITH_QTY>0 AND ITH_FORM!='TRFIN-FG'
	GROUP BY ITH_ITMCD
	)  VINC ON VSTK.ITH_ITMCD=VINC.ITH_ITMCD
) VMAIN

WHERE DAYLEFT>7
GO
/****** Object:  Table [dbo].[SISCN_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SISCN_TBL](
	[SISCN_DOC] [varchar](50) NULL,
	[SISCN_DOCREFF] [varchar](50) NULL,
	[SISCN_SER] [varchar](50) NOT NULL,
	[SISCN_SERQTY] [decimal](12, 3) NULL,
	[SISCN_PLLT] [varchar](50) NULL,
	[SISCN_LINENO] [varchar](50) NULL,
	[SISCN_ISFIFO] [char](1) NULL,
	[SISCN_LUPDT] [datetime] NULL,
	[SISCN_USRID] [varchar](50) NULL,
 CONSTRAINT [PK_SISCN_TBL] PRIMARY KEY CLUSTERED 
(
	[SISCN_SER] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[DLVH_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[DLVH_TBL](
	[DLVH_ID] [varchar](50) NOT NULL,
	[DLVH_PEMBERITAHU] [varchar](50) NULL,
	[DLVH_JABATAN] [varchar](50) NULL,
	[DLVH_TRANS] [varchar](15) NULL,
	[DLVH_DRIVER_NAME] [varchar](50) NULL,
	[DLVH_CODRIVER_NAME] [varchar](50) NULL,
	[DLVH_ACT_TRANS] [varchar](15) NULL,
	[updated_at] [datetime] NULL,
	[updated_by] [varchar](9) NULL,
	[gate_out_done_at] [datetime] NULL,
	[SUB_CONSIGN] [varchar](12) NULL,
 CONSTRAINT [PK_DLVH_TBL] PRIMARY KEY CLUSTERED 
(
	[DLVH_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[v_delivery_not_gate_out_yet]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO








CREATE VIEW [dbo].[v_delivery_not_gate_out_yet] as 
select DLV_ID,ISNULL(max(SI_HRMRK),max(SI_OTHRMRK)) PLANT,DLV_BCDATE, MAX(DLV_CUSTDO) CUSTDO,MAX(DLV_RMRK) DLV_RMRK, DLVH_ACT_TRANS from DLV_TBL 
LEFT JOIN SISCN_TBL ON DLV_SER=SISCN_SER
LEFT JOIN SI_TBL ON SISCN_LINENO=SI_LINENO
LEFT JOIN (SELECT ITH_SER FROM ITH_TBL WHERE  ITH_FORM='OUT-SHP-FG') V1 ON DLV_SER=ITH_SER
LEFT JOIN DLVH_TBL ON DLV_ID=DLVH_ID
WHERE  ITH_SER IS NULL AND DLV_BCDATE IS NOT NULL and DLV_SER!='' AND DLVH_ACT_TRANS IS NOT NULL
GROUP BY DLV_ID,DLV_BCDATE,DLVH_ACT_TRANS
GO
/****** Object:  View [dbo].[ENG_ASSY_TYPE]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[ENG_ASSY_TYPE] AS
SELECT REPLACE(A.ITMCD, '-', '') ASSYCD
	,REPLACE(RIGHT(A.PRDNO, 8), ')', '') TYPE
	,SUBSTRING(REPLACE(RIGHT(A.PRDNO, 8), ')', ''), CHARINDEX('(', REPLACE(RIGHT(A.PRDNO, 8), ')', '')) + 1, 7) TYPE_
	,A.PRDNO
FROM SMT_ENG.dbo.TECPRT A
WHERE CHARINDEX('(', REPLACE(RIGHT(A.PRDNO, 8), ')', '')) > 0
GROUP BY ITMCD,PRDNO
GO
/****** Object:  View [dbo].[ENG_ASSY_TYPE_FIX]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[ENG_ASSY_TYPE_FIX] AS
SELECT *,CASE WHEN LEN(TYPE_) = 5 THEN RIGHT(TYPE_,1) ELSE RIGHT(TYPE_,2) END TYPE_FIX FROM ENG_ASSY_TYPE
WHERE TYPE_!='STD'
GO
/****** Object:  View [dbo].[vr_vis_fg]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO








CREATE view [dbo].[vr_vis_fg] as



SELECT * FROM
(SELECT  v1.*,ITH_LOC,SER_ITMID from
(select ITH_WH,ITH_SER,SUM(ITH_QTY) ITH_QTY, MAX(ITH_LUPDT) LTS_TIME, MAX(isnull(ITH_LINE,'')) ITH_LINE  
from ITH_TBL WHERE ITH_WH='AFWH3' 
GROUP BY ITH_WH,ITH_SER
HAVING SUM(ITH_QTY) >0) v1
LEFT join ITH_TBL a ON v1.ITH_SER=a.ITH_SER and a.ITH_WH=v1.ITH_WH and isnull(v1.LTS_TIME,GETDATE())=isnull(a.ITH_LUPDT,GETDATE()) 
AND a.ITH_QTY>0
left JOIN SER_TBL ON a.ITH_SER=SER_ID
) x1



GO
/****** Object:  View [dbo].[DIFFERENT_TYPE_ASSY_WO]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[DIFFERENT_TYPE_ASSY_WO] AS
SELECT A.*
	,B.TYPE_FIX
	,CASE 
		WHEN SUBSTRING(A.PDPP_WONO, 8, 1) = '-'
			THEN SUBSTRING(A.PDPP_WONO, 5, 1)
		ELSE SUBSTRING(A.PDPP_WONO, 5, 2)
		END WOTYPE
FROM XWO A
LEFT JOIN ENG_ASSY_TYPE_FIX B ON A.PDPP_MDLCD = B.ASSYCD
WHERE A.PDPP_COMFG != '0'
	AND A.PDPP_BSGRP = 'PSI1PPZIEP'
	AND TYPE_FIX IS NOT NULL
	AND ISNULL(TYPE_FIX, '') != CASE 
		WHEN SUBSTRING(A.PDPP_WONO, 8, 1) = '-'
			THEN SUBSTRING(A.PDPP_WONO, 5, 1)
		ELSE SUBSTRING(A.PDPP_WONO, 5, 2)
		END
	AND YEAR(PDPP_ISUDT)>=2022
GO
/****** Object:  View [dbo].[v_infoscntoday_prd]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[v_infoscntoday_prd] 
as
SELECT        a.ITH_ITMCD, b.MITM_ITMD1, a.ITH_DOC, a.ITH_QTY, b.MITM_STKUOM, c.MSTEMP_FNM, a.ITH_LUPDT, a.ITH_SER
FROM            dbo.ITH_TBL AS a INNER JOIN
                         dbo.MITM_TBL AS b ON a.ITH_ITMCD = b.MITM_ITMCD INNER JOIN
                         dbo.MSTEMP_TBL AS c ON a.ITH_USRID = c.MSTEMP_ID
WHERE        (a.ITH_DATE = CONVERT(date, GETDATE())) AND (a.ITH_FORM = 'INC-PRD-FG')
GO
/****** Object:  View [dbo].[XPIS2]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XPIS2] AS
select  * from SRVMEGA.PSI_MEGAEMS.dbo.PIS2_TBL
GO
/****** Object:  View [dbo].[v_sim_not_in_psn]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE view [dbo].[v_sim_not_in_psn] as
select * from
(select PIS2_DOCNO PIS2_DOCNO,MAX(PIS2_LUPDT) LUPDTD,PIS2_BSGRP PIS2_BSGRP from XPIS2 where PIS2_DOCNO not like '%simulasi%' and
PIS2_DOCNO not like '%test%' --AND PIS0_PSNFG=0
group by PIS2_DOCNO, PIS2_BSGRP) vpis2
left join (
select PPSN1_DOCNO from XPPSN1 group by PPSN1_DOCNO
) vpsn_h on vpis2.PIS2_DOCNO=PPSN1_DOCNO
where PPSN1_DOCNO is null AND YEAR(LUPDTD)>=YEAR(GETDATE())-1
GO
/****** Object:  Table [dbo].[RCVSCN_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RCVSCN_TBL](
	[RCVSCN_ID] [varchar](20) NOT NULL,
	[RCVSCN_DONO] [varchar](100) NOT NULL,
	[RCVSCN_LOCID] [varchar](50) NULL,
	[RCVSCN_ITMCD] [varchar](50) NULL,
	[RCVSCN_LOTNO] [varchar](50) NULL,
	[RCVSCN_QTY] [decimal](12, 3) NULL,
	[RCVSCN_SAVED] [char](1) NULL,
	[RCVSCN_LUPDT] [datetime] NULL,
	[RCVSCN_USRID] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[v_rcvbc]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_rcvbc]
AS
SELECT DISTINCT dbo.RCVSCN_TBL.RCVSCN_ITMCD, dbo.RCVSCN_TBL.RCVSCN_LOTNO, dbo.RCV_TBL.RCV_DONO, dbo.RCV_TBL.RCV_BCTYPE, dbo.RCV_TBL.RCV_BCNO, dbo.RCV_TBL.RCV_BCDATE, dbo.RCV_TBL.RCV_RPNO
FROM        dbo.RCVSCN_TBL INNER JOIN
                  dbo.RCV_TBL ON dbo.RCVSCN_TBL.RCVSCN_DONO = dbo.RCV_TBL.RCV_DONO AND dbo.RCVSCN_TBL.RCVSCN_ITMCD = dbo.RCV_TBL.RCV_ITMCD
GO
/****** Object:  View [dbo].[vdlv_unconfirmed]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[vdlv_unconfirmed] as
select DLV_ID,DLV_DATE,DLV_POSTTM,MSTEMP_FNM from DLV_TBL 
INNER JOIN MSTEMP_TBL ON DLV_POST=MSTEMP_ID
WHERE DLV_POSTTM IS NOT NULL
GROUP BY DLV_ID,DLV_DATE,DLV_POST,DLV_POSTTM,MSTEMP_FNM
GO
/****** Object:  View [dbo].[v_sim_psn_ready]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[v_sim_psn_ready] as
select PPSN1_WONO from
(select PIS2_WONO from XPIS2 group by PIS2_WONO) v1
inner join (select PPSN1_WONO from XPPSN1 group by PPSN1_WONO) v2 on PIS2_WONO=PPSN1_WONO
where PIS2_WONO is not null and PPSN1_WONO is not null


GO
/****** Object:  View [dbo].[v_availablejob_fg_wh]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_availablejob_fg_wh] as
SELECT * FROM
(SELECT SER_DOC FROM
(select ITH_SER,SER_DOC,SUM(ITH_QTY) STKQTY from ITH_TBL
LEFT JOIN SER_TBL ON ITH_SER=SER_ID
WHERE ITH_WH='AFWH3'
group by ITH_SER,SER_DOC
having sum(ITH_QTY)>0) V1
GROUP BY SER_DOC) VJOBFGWH
GO
/****** Object:  Table [dbo].[DLVPRC_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[DLVPRC_TBL](
	[DLVPRC_SER] [varchar](21) NOT NULL,
	[DLVPRC_LINENO] [int] NOT NULL,
	[DLVPRC_PRC] [decimal](15, 6) NULL,
	[DLVPRC_QTY] [int] NULL,
	[DLVPRC_SILINE] [varchar](25) NULL,
	[DLVPRC_TXID] [varchar](50) NULL,
	[DLVPRC_CPO] [varchar](100) NULL,
	[DLVPRC_CPOLINE] [varchar](100) NULL,
	[DLVPRC_CREATEDAT] [datetime] NULL,
	[DLVPRC_CREATEDBY] [varchar](15) NULL,
 CONSTRAINT [PK__DLVPRC_T__151F189049126AF9] PRIMARY KEY CLUSTERED 
(
	[DLVPRC_SER] ASC,
	[DLVPRC_LINENO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[wms_v_outpabean]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO











CREATE view [dbo].[wms_v_outpabean] as

SELECT DLV_BCDATE,DLV_ID,DLV_CUSTDO,DLV_CONSIGN,NOMAJU,NOMPEN,INVDT,DLV_INVNO,DLV_SMTINVNO,SER_ITMID,RTRIM(MITM_ITMD1) MITM_ITMD1,DLVPRC_CPO,DLVPRC_QTY,DLVPRC_PRC,(DLVPRC_QTY*DLVPRC_PRC) AMOUNT,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,MITM_HSCD,
DLV_BCTYPE,DLV_ZJENIS_TPB_TUJUAN,TGLPEN,RTRIM(MITM_STKUOM) MITM_STKUOM,DLV_PURPOSE,MITM_MODEL,DLV_SPPBDOC,VALUTA
FROM 
		(select DLV_ID,MIN(DLV_BCDATE) DLV_BCDATE,max(DLV_CONSIGN) DLV_CONSIGN,max(DLV_INVDT) INVDT,MAX(DLV_INVNO) DLV_INVNO, MAX(DLV_SMTINVNO) DLV_SMTINVNO,MAX(DLV_ZNOMOR_AJU) NOMAJU,max(DLV_NOPEN) NOMPEN,MAX(DLV_CUSTDO) DLV_CUSTDO 
		,MAX(MDEL_ZNAMA) MDEL_ZNAMA,MAX(MDEL_ADDRCUSTOMS) MDEL_ADDRCUSTOMS,max(DLV_BCTYPE) DLV_BCTYPE,max(DLV_ZJENIS_TPB_TUJUAN) DLV_ZJENIS_TPB_TUJUAN,max(DLV_RPDATE) TGLPEN,MAX(DLV_PURPOSE) DLV_PURPOSE
		,max(DLV_SPPBDOC) DLV_SPPBDOC,MAX(MCUS_CURCD) VALUTA
		from DLV_TBL
		LEFT JOIN MDEL_TBL ON DLV_CONSIGN=MDEL_DELCD
		LEFT JOIN MCUS_TBL ON DLV_CUSTCD=MCUS_CUSCD
		where DLV_BCDATE is not null AND DLV_SER!=''  AND DLV_ID NOT LIKE '%RTN%'
		GROUP BY DLV_ID) VDELV
		LEFT JOIN
		(
		SELECT DLVPRC_TXID,SER_ITMID,DLVPRC_PRC,SUM(DLVPRC_QTY) DLVPRC_QTY,DLVPRC_CPO FROM DLVPRC_TBL 
		LEFT JOIN SER_TBL ON DLVPRC_SER=SER_ID
		GROUP BY DLVPRC_TXID,SER_ITMID,DLVPRC_PRC,DLVPRC_CPO
		) VPRC ON DLV_ID=DLVPRC_TXID	
		left join MITM_TBL ON SER_ITMID=MITM_ITMCD
		WHERE DLVPRC_PRC IS NOT NULL
		
GO
/****** Object:  View [dbo].[wms_v_discrepancy_prd_qc]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[wms_v_discrepancy_prd_qc] as
SELECT SER_ITMID,RTRIM(MITM_ITMD1) ITMD1,SER_LOTNO,SER_DOC,SER_QTY,ITH_SER,CONCAT(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,LUPDT,SER_BSGRP,SER_RMRK
,DATEDIFF(DAY,LUPDT,GETDATE()) DAYLEFT FROM 
(select ITH_SER,SUM(ITH_QTY) STKQT,MAX(ITH_LUPDT) LUPDT, MAX(ITH_DOC) DOC,MAX(ITH_USRID) ITH_USRID from ITH_TBL
where ITH_WH='ARPRD1' AND ITH_SER IS NOT NULL
GROUP BY ITH_SER
HAVING SUM(ITH_QTY)>0) VITH
LEFT JOIN SER_TBL ON ITH_SER=SER_ID
LEFT JOIN MITM_TBL ON SER_ITMID=MITM_ITMCD
LEFT JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
--WHERE DATEDIFF(DAY,LUPDT,GETDATE())>7
GO
/****** Object:  Table [dbo].[MITMSA_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MITMSA_TBL](
	[MITMSA_ITMCD] [varchar](50) NOT NULL,
	[MITMSA_ITMCDS] [varchar](50) NOT NULL,
	[MITMSA_MDLCD] [varchar](50) NOT NULL,
	[MITMSA_LUPDT] [datetime] NULL,
	[MITMSA_USRID] [varchar](25) NULL,
	[MITMSA_DELDT] [datetime] NULL,
	[MITMSA_DELBY] [varchar](25) NULL,
	[MITMSA_RMRK] [varchar](100) NULL,
	[MITMSA_RELATION_TYPE] [varchar](1) NULL,
	[MITMSA_LOC_AT_PCB] [varchar](5) NULL,
 CONSTRAINT [PK__MITMSA_T__4D19F26EDF9CD27B] PRIMARY KEY CLUSTERED 
(
	[MITMSA_ITMCD] ASC,
	[MITMSA_ITMCDS] ASC,
	[MITMSA_MDLCD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[VMITMSA]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE VIEW [dbo].[VMITMSA] AS
SELECT RTRIM(MITMSA_ITMCD) MITMSA_ITMCD,RTRIM(MITMSA_ITMCDS) MITMSA_ITMCDS,RTRIM(A.MITM_SPTNO) SPTNO
, RTRIM(B.MITM_SPTNO) SPTNOS,RTRIM(MITMSA_MDLCD) FG, RTRIM(C.MITM_ITMD1) FGNM 
, ISNULL(MITMSA_RMRK,'') MITMSA_RMRK
FROM MITMSA_TBL LEFT JOIN
MITM_TBL A ON MITMSA_ITMCD=A.MITM_ITMCD
LEFT JOIN MITM_TBL B ON MITMSA_ITMCDS=B.MITM_ITMCD
LEFT JOIN MITM_TBL C ON MITMSA_MDLCD=C.MITM_ITMCD
WHERE MITMSA_DELDT IS NULL
GO
/****** Object:  View [dbo].[XMSPP]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XMSPP] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.MSPP_TBL
GO
/****** Object:  View [dbo].[XMSPP_HIS]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[XMSPP_HIS] AS
SELECT DISTINCT RTRIM(MSPP_MDLCD) MSPP_MDLCD, RTRIM(MSPP_BOMPN) MSPP_BOMPN,RTRIM(MSPP_SUBPN) MSPP_SUBPN,MSPP_ACTIVE FROM XMSPP 
GO
/****** Object:  View [dbo].[wms_v_outpabean_rtn]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE VIEW [dbo].[wms_v_outpabean_rtn] as
SELECT DLV_BCDATE,DLV_ID,DLV_CUSTDO,DLV_CONSIGN,DLV_ZNOMOR_AJU NOMAJU,DLV_NOPEN NOMPEN,DLV_INVDT INVDT,DLV_INVNO DLV_INVNO,
DLV_SMTINVNO DLV_SMTINVNO,SER_ITMID, RTRIM(MITM_ITMD1) MITM_ITMD1, '' DLVPRC_CPO, SUM(INTQTY) DLVPRC_QTY,
RCV_PRPRC DLVPRC_PRC, RCV_PRPRC*SUM(INTQTY) AMOUNT, MDEL_ZNAMA,MDEL_ADDRCUSTOMS,MITM_HSCD,
DLV_BCTYPE,DLV_ZJENIS_TPB_TUJUAN,DLV_RPDATE TGLPEN,RTRIM(MITM_STKUOM) MITM_STKUOM,DLV_PURPOSE,MITM_MODEL,max(DLV_SPPBDOC) DLV_SPPBDOC,MAX(MCUS_CURCD) VALUTA from DLV_TBL
                LEFT JOIN (
                    SELECT ITH_REMARK EXTLBL
                        ,ABS(sum(ITH_QTY)) INTQTY
                        ,ITH_DOC
                        ,min(ITH_ITMCD) OLDITEM
                    FROM ITH_TBL
                    WHERE ITH_QTY < 0 AND ITH_WH in ('AFQART','AFQART2')
                    GROUP BY ITH_REMARK
                        ,ITH_DOC
                    ) VMAP ON DLV_SER = EXTLBL	
				LEFT JOIN SER_TBL ON DLV_SER=SER_ID
				LEFT JOIN MITM_TBL ON SER_ITMID=MITM_ITMCD
				LEFT JOIN (
                SELECT RCV_DONO
                    ,RCV_RPNO
                    ,RCV_BCNO
                    ,RCV_KPPBC
                    ,RCV_BCTYPE
                    ,RCV_RPDATE
                    ,isnull(MAX(RCV_ZNOURUT),0) RCV_ZNOURUT
                    ,MAX(RCV_PRPRC) RCV_PRPRC
                    ,RCV_ITMCD
                    ,MAX(RCV_HSCD) RCV_HSCD
                    ,MAX(RCV_BM) RCV_BM
                FROM RCV_TBL
				
                GROUP BY RCV_DONO
                    ,RCV_RPNO
                    ,RCV_BCNO
                    ,RCV_KPPBC
                    ,RCV_BCTYPE
                    ,RCV_RPDATE	
                    ,RCV_ITMCD		
                ) VRCVCUST ON ITH_DOC = RCV_DONO AND OLDITEM=RCV_ITMCD
				LEFT JOIN MDEL_TBL ON DLV_CONSIGN=MDEL_DELCD
				LEFT JOIN MCUS_TBL ON DLV_CUSTCD=MCUS_CUSCD
                WHERE ITH_DOC IS NOT NULL AND DLV_ZNOMOR_AJU IS NOT NULL				
GROUP BY DLV_BCDATE,DLV_ID,DLV_CUSTDO,DLV_CONSIGN,DLV_ZNOMOR_AJU,DLV_NOPEN,DLV_INVDT,DLV_INVNO,
DLV_SMTINVNO,SER_ITMID, MITM_ITMD1,
RCV_PRPRC,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,MITM_HSCD,
DLV_BCTYPE,DLV_ZJENIS_TPB_TUJUAN,DLV_RPDATE,MITM_STKUOM,DLV_PURPOSE,MITM_MODEL
GO
/****** Object:  View [dbo].[v_sim_from_last_month]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_sim_from_last_month] as

select * from
(select PIS2_DOCNO,MAX(PIS2_LUPDT) LUPDTD,PIS2_BSGRP from XPIS2 where PIS2_DOCNO not like '%simulasi%' and
PIS2_DOCNO not like '%test%' 
group by PIS2_DOCNO, PIS2_BSGRP) vpis2
left join (
select PPSN1_DOCNO from XPPSN1 group by PPSN1_DOCNO
) vpsn_h on vpis2.PIS2_DOCNO=PPSN1_DOCNO
where LUPDTD>= DATEADD(YEAR, -1, GETDATE())
GO
/****** Object:  Table [dbo].[SPLSCN_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SPLSCN_TBL](
	[SPLSCN_ID] [varchar](50) NOT NULL,
	[SPLSCN_DOC] [varchar](50) NOT NULL,
	[SPLSCN_CAT] [varchar](50) NOT NULL,
	[SPLSCN_LINE] [varchar](50) NOT NULL,
	[SPLSCN_FEDR] [varchar](50) NOT NULL,
	[SPLSCN_ORDERNO] [varchar](50) NULL,
	[SPLSCN_ITMCD] [varchar](50) NOT NULL,
	[SPLSCN_LOTNO] [varchar](50) NULL,
	[SPLSCN_SAVED] [char](1) NULL,
	[SPLSCN_QTY] [decimal](12, 3) NOT NULL,
	[SPLSCN_LUPDT] [datetime] NOT NULL,
	[SPLSCN_USRID] [varchar](50) NOT NULL,
	[SPLSCN_EXPORTED] [varchar](50) NULL,
	[SPLSCN_MC] [varchar](40) NULL,
	[SPLSCN_PROCD] [varchar](20) NULL,
	[SPLSCN_UNQCODE] [varchar](21) NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[VKITTINGSTATUS]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE VIEW [dbo].[VKITTINGSTATUS] AS SELECT VMAIN.SPL_DOC
,CASE WHEN ISNULL(VAL_CHIP,0) = 0  THEN 'O' 
	WHEN ISNULL(VAL_CHIP,0) >0 AND ISNULL(VAL_CHIP,0) = ISNULL(ROW_CHIP,0)  THEN 'X'
	WHEN ISNULL(VAL_CHIP,0) >0 AND ISNULL(VAL_CHIP,0) != ISNULL(ROW_CHIP,0) THEN 'T'
	ELSE '-'
	END STSCHIP
,CASE WHEN ISNULL(VAL_HW,0) = 0  THEN 'O' 
	WHEN ISNULL(VAL_HW,0) >0 AND ISNULL(VAL_HW,0) = ISNULL(ROW_HW,0)  THEN 'X'
	WHEN ISNULL(VAL_HW,0) >0 AND ISNULL(VAL_HW,0) != ISNULL(ROW_HW,0) THEN 'T'
	ELSE '-'
	END STSHW
,CASE WHEN ISNULL(VAL_IC,0) = 0  THEN 'O' 
	WHEN ISNULL(VAL_IC,0) >0 AND ISNULL(VAL_IC,0) = ISNULL(ROW_IC,0)  THEN 'X'
	WHEN ISNULL(VAL_IC,0) >0 AND ISNULL(VAL_IC,0) != ISNULL(ROW_IC,0) THEN 'T'
	END STSIC
,CASE WHEN ISNULL(VAL_KANBAN,0) = 0  THEN 'O' 
	WHEN ISNULL(VAL_KANBAN,0) >0 AND ISNULL(VAL_KANBAN,0) = ISNULL(ROW_KANBAN,0)  THEN 'X'
	WHEN ISNULL(VAL_KANBAN,0) >0 AND ISNULL(VAL_KANBAN,0) != ISNULL(ROW_KANBAN,0) THEN 'T'
	ELSE '-'
	END STSKANBAN
,CASE WHEN ISNULL(VAL_PCB,0) = 0  THEN 'O' 
	WHEN ISNULL(VAL_PCB,0) >0 AND ISNULL(VAL_PCB,0) = ISNULL(ROW_PCB,0)  THEN 'X'
	WHEN ISNULL(VAL_PCB,0) >0 AND ISNULL(VAL_PCB,0) != ISNULL(ROW_PCB,0) THEN 'T'
	ELSE '-'
	END STSPCB
,CASE WHEN ISNULL(VAL_PREPARE,0) = 0  THEN 'O' 
	WHEN ISNULL(VAL_PREPARE,0) >0 AND ISNULL(VAL_PREPARE,0) = ISNULL(ROW_PREPARE,0)  THEN 'X'
	WHEN ISNULL(VAL_PREPARE,0) >0 AND ISNULL(VAL_PREPARE,0) != ISNULL(ROW_PREPARE,0) THEN 'T'
	ELSE '-'
	END STSPREPARE
,CASE WHEN ISNULL(VAL_SP,0) = 0  THEN 'O' 
	WHEN ISNULL(VAL_SP,0) >0 AND ISNULL(VAL_SP,0) = ISNULL(ROW_SP,0)  THEN 'X'
	WHEN ISNULL(VAL_SP,0) >0 AND ISNULL(VAL_SP,0) != ISNULL(ROW_SP,0) THEN 'T'
	ELSE '-'
	END STSSP,
	PPSN1_BSGRP
FROM
(
SELECT VX.SPL_DOC
,SUM(CASE WHEN VX.SPL_CAT = 'CHIP' THEN 1 END) VAL_CHIP
,SUM(CASE WHEN VX.SPL_CAT = 'HW' THEN 1 END) VAL_HW
,SUM(CASE WHEN VX.SPL_CAT = 'IC' THEN 1 END) VAL_IC
,SUM(CASE WHEN VX.SPL_CAT = 'KANBAN' THEN 1 END) VAL_KANBAN
,SUM(CASE WHEN VX.SPL_CAT = 'PCB' THEN 1 END) VAL_PCB
,SUM(CASE WHEN VX.SPL_CAT = 'PREPARE' THEN 1 END) VAL_PREPARE
,SUM(CASE WHEN VX.SPL_CAT = 'SP' THEN 1 END) VAL_SP
,PPSN1_BSGRP
FROM
	(SELECT V1.*,ISNULL(SUPQT,0) SUPQT,SPL_BG PPSN1_BSGRP FROM
			(SELECT SPL_BG,SPL_DOC,SPL_LINE,SPL_FEDR,SPL_ORDERNO,SPL_CAT,SPL_ITMCD,SUM(SPL_QTYREQ) REQQT 
				FROM SPL_TBL 			
				GROUP BY SPL_DOC,SPL_LINE,SPL_FEDR,SPL_ORDERNO,SPL_CAT,SPL_ITMCD,SPL_BG
			) V1
			LEFT JOIN 
			(SELECT SPLSCN_DOC,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ORDERNO,SPLSCN_CAT,SPLSCN_ITMCD,SUM(SPLSCN_QTY) SUPQT 
				FROM SPLSCN_TBL        
				GROUP BY SPLSCN_DOC,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ORDERNO,SPLSCN_CAT,SPLSCN_ITMCD
			) V2 ON SPL_DOC=SPLSCN_DOC AND SPL_LINE=SPLSCN_LINE AND SPL_FEDR=SPLSCN_FEDR AND SPL_CAT=SPLSCN_CAT AND SPL_ITMCD=SPLSCN_ITMCD and SPL_ORDERNO=SPLSCN_ORDERNO
						
		WHERE REQQT>ISNULL(SUPQT,0)
	) VX
GROUP BY VX.SPL_DOC,PPSN1_BSGRP
) VMAIN
LEFT JOIN
(
	SELECT SPL_DOC ,SUM(CASE WHEN SPL_CAT = 'CHIP' THEN 1 END) ROW_CHIP
	,SUM(CASE WHEN SPL_CAT = 'HW' THEN 1 END) ROW_HW
	,SUM(CASE WHEN SPL_CAT = 'IC' THEN 1 END) ROW_IC
	,SUM(CASE WHEN SPL_CAT = 'KANBAN' THEN 1 END) ROW_KANBAN
	,SUM(CASE WHEN SPL_CAT = 'PCB' THEN 1 END) ROW_PCB
	,SUM(CASE WHEN SPL_CAT = 'PREPARE' THEN 1 END) ROW_PREPARE
	,SUM(CASE WHEN SPL_CAT = 'SP' THEN 1 END) ROW_SP 
	FROM 
		(SELECT SPL_DOC,SPL_CAT,SPL_FEDR,SPL_ORDERNO,SPL_ITMCD FROM SPL_TBL
		GROUP BY SPL_DOC,SPL_CAT,SPL_FEDR,SPL_ORDERNO,SPL_ITMCD) VGRUP
	GROUP BY SPL_DOC
) VREQ ON VMAIN.SPL_DOC=VREQ.SPL_DOC
GO
/****** Object:  Table [dbo].[SERD_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SERD_TBL](
	[SERD_PSNNO] [varchar](50) NOT NULL,
	[SERD_LINENO] [varchar](20) NOT NULL,
	[SERD_CAT] [varchar](20) NOT NULL,
	[SERD_FR] [char](1) NOT NULL,
	[SERD_PROCD] [varchar](25) NULL,
	[SERD_JOB] [varchar](50) NOT NULL,
	[SERD_QTPER] [decimal](12, 3) NULL,
	[SERD_MC] [varchar](20) NULL,
	[SERD_MCZ] [varchar](20) NULL,
	[SERD_MSFLG] [varchar](50) NULL,
	[SERD_ITMCD] [varchar](50) NOT NULL,
	[SERD_QTYREQ] [bigint] NULL,
	[SERD_QTY] [bigint] NOT NULL,
	[SERD_LOTNO] [varchar](50) NULL,
	[SERD_MSCANTM] [datetime] NULL,
	[SERD_REMARK] [varchar](25) NULL,
	[SERD_MPART] [varchar](50) NULL,
	[SERD_USRID] [varchar](15) NULL,
	[SERD_LUPDT] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[WMS_V_WOPRD_UNCALCULATED]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[WMS_V_WOPRD_UNCALCULATED] AS
SELECT VTODAY_WO.*
FROM (
	SELECT ITH_DOC
	FROM v_ith_tblc
	WHERE ITH_DATEC = CONVERT(DATE, GETDATE())
		AND ITH_WH = 'ARPRD1'
		AND ITH_FORM IN ('INC-PRD-FG','INC')
	GROUP BY ITH_DOC
	) VTODAY_WO
LEFT JOIN (
	SELECT SERD_JOB
	FROM SERD_TBL
	GROUP BY SERD_JOB
	) VCALCUALTION_WO ON ITH_DOC = SERD_JOB
WHERE SERD_JOB IS NULL
GO
/****** Object:  View [dbo].[vrcv_tblg]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[vrcv_tblg]
AS
SELECT        RCV_DONO, RCV_ITMCD, SUM(RCV_QTY) AS RCV_QTY, RCV_WH
FROM            dbo.RCV_TBL
GROUP BY RCV_DONO, RCV_ITMCD, RCV_WH
GO
/****** Object:  View [dbo].[XPIS3]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XPIS3] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.PIS3_TBL
GO
/****** Object:  View [dbo].[vcheck_sususan_bahan_baku]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[vcheck_sususan_bahan_baku]
as
SELECT V1.*,SER_DOC,SER_ITMID,MYPER,ISNULL(SERD2_QTPER,0) CALPER,ISNULL(SER_RMUSE_COMFG,'-') FLG,RTRIM(MITM_ITMD1) MITM_ITMD1,SER_QTY  FROM
(SELECT ITH_SER FROM ITH_TBL WHERE ITH_WH='AFWH3'
GROUP BY ITH_SER
HAVING SUM(ITH_QTY)>0) V1
INNER JOIN SER_TBL ON  ITH_SER=SER_ID
LEFT JOIN (
select PIS3_WONO,SUM(MYPER) MYPER from 
        (select PDPP_MDLCD,PIS3_WONO,PIS3_LINENO,PIS3_FR,PIS3_PROCD,PIS3_MC,PIS3_MCZ,SUM(PIS3_REQQT) PIS3_REQQTSUM,SUM(PIS3_REQQT)/PDPP_WORQT MYPER,max(PIS3_ITMCD) PIS3_ITMCD,PIS3_MPART from XPIS3 
                LEFT JOIN XWO ON PIS3_WONO=PDPP_WONO
				RIGHT JOIN ( SELECT PPSN1_WONO,MAX(PPSN1_DOCNO) PSN1DOCNO FROM XPPSN1 GROUP BY PPSN1_WONO ) VPSN ON PIS3_WONO=PPSN1_WONO AND PIS3_DOCNO=PSN1DOCNO                
                GROUP BY PIS3_WONO,PIS3_LINENO,PIS3_MC,PIS3_MCZ,PDPP_WORQT,PDPP_MDLCD,PIS3_FR,PIS3_PROCD,PIS3_MPART) V1
        GROUP BY PIS3_WONO
) VREQ ON SER_DOC=PIS3_WONO
left join (
SELECT SERD2_JOB,SUM(SERD2_QTPER) SERD2_QTPER,max(SERD2_SER) SERD2_SER,max(SERD2_FGQTY) SERD2_FGQTY FROM vserd2_cims SERDA              
        group by SERD2_JOB,SERD2_SER
) VCAL ON ITH_SER=SERD2_SER
LEFT JOIN MITM_TBL ON SER_ITMID=MITM_ITMCD
WHERE ISNULL(MYPER,0) > ISNULL(SERD2_QTPER,0)  OR (ISNULL(MYPER,0) =0 )
GO
/****** Object:  Table [dbo].[DLVRMDOC_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[DLVRMDOC_TBL](
	[DLVRMDOC_TXID] [varchar](50) NOT NULL,
	[DLVRMDOC_ITMID] [varchar](50) NULL,
	[DLVRMDOC_ITMQT] [decimal](12, 3) NULL,
	[DLVRMDOC_DO] [varchar](50) NULL,
	[DLVRMDOC_AJU] [varchar](50) NULL,
	[DLVRMDOC_NOPEN] [varchar](50) NULL,
	[DLVRMDOC_PRPRC] [decimal](15, 6) NULL,
	[DLVRMDOC_ZPRPRC] [decimal](15, 6) NULL,
	[DLVRMDOC_TYPE] [varchar](50) NULL,
	[DLVRMDOC_LINE] [int] NOT NULL,
 CONSTRAINT [PK__DLVRMDOC__1328C02A312CF783] PRIMARY KEY CLUSTERED 
(
	[DLVRMDOC_TXID] ASC,
	[DLVRMDOC_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[wms_v_outpabean_oth]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO










CREATE VIEW [dbo].[wms_v_outpabean_oth] as
SELECT DLV_BCDATE
	,DLV_ID
	,DLV_CUSTDO
	,DLV_CONSIGN
	,NOMAJU
	,NOMPEN
	,INVDT
	,DLV_INVNO
	,DLV_SMTINVNO
	,DLVRMDOC_ITMID SER_ITMID
	,MITM_ITMD1
	,'' DLVPRC_CPO
	,DLVPRC_QTY
	,DLVRMDOC_PRPRC DLVPRC_PRC
	,DLVPRC_QTY * DLVRMDOC_PRPRC AMOUNT
	,MDEL_ZNAMA
	,MDEL_ADDRCUSTOMS
	,MITM_HSCD
	,DLV_BCTYPE
	,DLV_ZJENIS_TPB_TUJUAN
	,TGLPEN
	,MITM_STKUOM
	,DLV_PURPOSE
	,MITM_MODEL
	,DLV_SPPBDOC
	,VALUTA
FROM (
	SELECT DLV_BCDATE
		,DLV_ID
		,DLV_CUSTDO
		,DLV_CONSIGN
		,DLV_ZNOMOR_AJU NOMAJU
		,DLV_NOPEN NOMPEN
		,DLV_INVDT INVDT
		,DLV_INVNO
		,DLV_SMTINVNO
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_BCTYPE
		,DLV_ZJENIS_TPB_TUJUAN
		,DLV_RPDATE TGLPEN
		,DLV_PURPOSE
		,max(DLV_SPPBDOC) DLV_SPPBDOC
		,MAX(ISNULL(MCUS_CURCD, MSUP_SUPCR)) VALUTA
	FROM DLV_TBL
	LEFT JOIN MDEL_TBL ON DLV_CONSIGN = MDEL_DELCD
	LEFT JOIN MITM_TBL ON DLV_ITMCD = MITM_ITMCD
	LEFT JOIN MCUS_TBL ON DLV_CUSTCD = MCUS_CUSCD
	LEFT JOIN MSUP_TBL ON DLV_CUSTCD = MSUP_SUPCD
	WHERE DLV_SER = ''
		AND MITM_MODEL != '8'
		AND isnull(DLV_NOAJU, '') != ''
	GROUP BY DLV_BCDATE
		,DLV_ID
		,DLV_CUSTDO
		,DLV_CONSIGN
		,DLV_ZNOMOR_AJU
		,DLV_NOPEN
		,DLV_INVDT
		,DLV_INVNO
		,DLV_SMTINVNO
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_BCTYPE
		,DLV_ZJENIS_TPB_TUJUAN
		,DLV_RPDATE
		,DLV_PURPOSE
	) VDLV
LEFT JOIN (
	SELECT DLVRMDOC_TXID
		,DLVRMDOC_ITMID
		,RTRIM(MITM_ITMD1) MITM_ITMD1
		,sum(DLVRMDOC_ITMQT) DLVPRC_QTY
		,DLVRMDOC_PRPRC
		,RCV_HSCD MITM_HSCD
		,rtrim(MITM_STKUOM) MITM_STKUOM
		,MITM_MODEL
	FROM DLVRMDOC_TBL
	LEFT JOIN MITM_TBL ON DLVRMDOC_ITMID = MITM_ITMCD
	LEFT JOIN (
		SELECT RCV_ITMCD
			,RCV_DONO
			,max(RCV_HSCD) RCV_HSCD
		FROM RCV_TBL
		GROUP BY RCV_ITMCD
			,RCV_DONO
		) vrcv ON DLVRMDOC_DO = RCV_DONO
		AND DLVRMDOC_ITMID = RCV_ITMCD
	GROUP BY DLVRMDOC_TXID
		,DLVRMDOC_ITMID
		,MITM_ITMD1
		,DLVRMDOC_PRPRC
		,RCV_HSCD
		,MITM_STKUOM
		,MITM_MODEL
	) vselect ON DLV_ID = DLVRMDOC_TXID

GO
/****** Object:  View [dbo].[VMINUS_STOCK]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[VMINUS_STOCK] AS
SELECT VITH.*,RTRIM(MITM_ITMD1) ITMD1,RTRIM(MITM_SPTNO) SPTNO FROM
(select ITH_WH,ITH_ITMCD,SUM(ITH_QTY) QTY from v_ith_tblc where ITH_WH in
('QA','ARWH1','ARWH2','ARWH0PD','AFWH3','NRWH2')
GROUP BY ITH_WH,ITH_ITMCD
HAVING SUM(ITH_QTY)<0) VITH
LEFT JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
GO
/****** Object:  View [dbo].[wms_v_unused_machine_and_equipment]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[wms_v_unused_machine_and_equipment]
AS
select MITM_ITMCD,PO_ITMCD,ITH_ITMCD from MITM_TBL 
LEFT JOIN (SELECT PO_ITMCD FROM PO_TBL GROUP BY PO_ITMCD)
VPO ON MITM_ITMCD=PO_ITMCD
LEFT JOIN (SELECT ITH_ITMCD FROM ITH_TBL GROUP BY ITH_ITMCD)
VITH ON MITM_ITMCD=ITH_ITMCD
WHERE MITM_MODEL='6' AND PO_ITMCD IS NULL AND ITH_ITMCD IS NULL
GO
/****** Object:  View [dbo].[v_delivery_unconfirmed]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO






CREATE VIEW [dbo].[v_delivery_unconfirmed] as 
select DLV_ID,ISNULL(max(SI_HRMRK),max(SI_OTHRMRK)) PLANT,DLV_BCDATE, MAX(DLV_CUSTDO) CUSTDO,MAX(DLV_RMRK) DLV_RMRK from DLV_TBL 
LEFT JOIN SISCN_TBL ON DLV_SER=SISCN_SER
LEFT JOIN SI_TBL ON SISCN_LINENO=SI_LINENO
LEFT JOIN (SELECT ITH_SER FROM ITH_TBL WHERE  ITH_FORM='OUT-SHP-FG') V1 ON DLV_SER=ITH_SER
LEFT JOIN DLVH_TBL ON DLV_ID=DLVH_ID
WHERE  ITH_SER IS NULL AND DLV_BCDATE IS NOT NULL and DLV_SER!='' AND DLVH_ACT_TRANS IS NULL
GROUP BY DLV_ID,DLV_BCDATE
GO
/****** Object:  Table [dbo].[SCNDOC_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SCNDOC_TBL](
	[SCNDOC_DOCNO] [varchar](50) NOT NULL,
	[SCNDOC_TYPE] [char](2) NOT NULL,
	[SCNDOC_USRID] [varchar](15) NOT NULL,
	[SCNDOC_SCANNEDAT] [datetime] NULL,
	[SCNDOC_CREATEDAT] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[SCNDOC_DOCNO] ASC,
	[SCNDOC_TYPE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[wms_v_scandoc_today]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[wms_v_scandoc_today] AS
select SCNDOC_DOCNO, CONCAT(isnull(MSTEMP_FNM,user_nicename), ' ',MSTEMP_LNM) USERNAME,SCNDOC_SCANNEDAT from SCNDOC_TBL 
left join MSTEMP_TBL ON SCNDOC_USRID=MSTEMP_ID
left join VNPSI_USERS on SCNDOC_USRID=ID COLLATE Japanese_CI_AS
where convert(date,SCNDOC_SCANNEDAT)=convert(date,getdate())
GO
/****** Object:  View [dbo].[v_assy_as_sub]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[v_assy_as_sub] as
select PWOP_BOMPN,MITM_ITMD1 from XPWOP LEFT JOIN
 XMITM_V ON PWOP_BOMPN=MITM_ITMCD
 WHERE MITM_MODEL='1'
 GROUP BY  PWOP_BOMPN,MITM_ITMD1
GO
/****** Object:  View [dbo].[XPPSN2]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XPPSN2] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.PPSN2_TBL
GO
/****** Object:  View [dbo].[XMBOM]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XMBOM] AS SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.MBOM_TBL
GO
/****** Object:  View [dbo].[VCIMS_MBOM_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[VCIMS_MBOM_TBL] AS
SELECT * FROM SRVCIMS.CIM_WMS.dbo.MBOM_TBL
GO
/****** Object:  View [dbo].[v_bom_mega_vs_cims]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_bom_mega_vs_cims] AS
SELECT
	mt.MITM_ITMCD,
	mt.MITM_ITMD1,
	MAX(CIMS.MBOM_BOMRV) AS REV_CIMS,
	MAX(MEGA.MBOM_BOMRV) AS REV_MEGA,
	JOB.PIS3_LINENO AS LINE_PROD,
	CASE WHEN PSN.PPSN1_MDLCD IS NOT NULL
		THEN 'YES'
		ELSE 'NO'
	END AS KITTING_STATUS
FROM PSI_WMS.dbo.MITM_TBL mt
LEFT JOIN (
	SELECT
		MBOM_MDLCD,
		MBOM_BOMRV,
		MBOM_PROCD
	FROM PSI_WMS.dbo.Vcims_mbom_tbl
	GROUP BY 
		MBOM_MDLCD,
		MBOM_BOMRV,
		MBOM_PROCD
) AS CIMS ON CIMS.MBOM_MDLCD = mt.MITM_ITMCD 
LEFT JOIN (
	SELECT
		MBOM_MDLCD,
		MBOM_BOMRV,
		MBOM_PROCD
	FROM PSI_WMS.dbo.XMBOM
	GROUP BY 
		MBOM_MDLCD,
		MBOM_BOMRV,
		MBOM_PROCD
) AS MEGA ON MEGA.MBOM_MDLCD = mt.MITM_ITMCD 
LEFT JOIN (
	SELECT 
		x.PIS3_MDLCD,
		x.PIS3_LINENO,
		x.PIS3_BOMRV
	FROM PSI_WMS.dbo.XPIS3 x
	GROUP BY
		x.PIS3_MDLCD,
		x.PIS3_LINENO,
		x.PIS3_BOMRV
) AS JOB 
	ON JOB.PIS3_MDLCD = mt.MITM_ITMCD 
LEFT JOIN (
	SELECT 
		xp1.PPSN1_MDLCD,
		xp1.PPSN1_BOMRV,
		SUM(xp2.PPSN2_PICKQT1) AS PPSN2_PICKQT1,
		SUM(xp2.PPSN2_PICKQT2) AS PPSN2_PICKQT2,
		SUM(xp2.PPSN2_PICKQT3) AS PPSN2_PICKQT3,
		SUM(xp2.PPSN2_PICKQT4) AS PPSN2_PICKQT4,
		SUM(xp2.PPSN2_PICKQT5) AS PPSN2_PICKQT5,
		SUM(xp2.PPSN2_PICKQT6) AS PPSN2_PICKQT6,
		SUM(xp2.PPSN2_PICKQT7) AS PPSN2_PICKQT7,
		SUM(xp2.PPSN2_PICKQT8) AS PPSN2_PICKQT8
	FROM PSI_WMS.dbo.XPPSN1 xp1
	INNER JOIN PSI_WMS.dbo.XPPSN2 xp2 
		ON xp1.PPSN1_PSNNO = xp2.PPSN2_PSNNO 
		AND xp1.PPSN1_PROCD = xp2.PPSN2_PROCD
		AND xp1.PPSN1_LINENO = xp2.PPSN2_LINENO
		AND xp1.PPSN1_FR = xp2.PPSN2_FR
	AND (
		xp2.PPSN2_PICKQT1 > 0
		OR xp2.PPSN2_PICKQT2 > 0
		OR xp2.PPSN2_PICKQT3 > 0
		OR xp2.PPSN2_PICKQT4 > 0
		OR xp2.PPSN2_PICKQT5 > 0
		OR xp2.PPSN2_PICKQT6 > 0
		OR xp2.PPSN2_PICKQT7 > 0
		OR xp2.PPSN2_PICKQT8 > 0
	)
	GROUP BY
		xp1.PPSN1_MDLCD,
		xp1.PPSN1_BOMRV
) PSN ON PSN.PPSN1_MDLCD = mt.MITM_ITMCD 
WHERE mt.MITM_MODEL = 1
GROUP BY
	mt.MITM_ITMCD,
	mt.MITM_ITMD1,
	PSN.PPSN1_MDLCD,
	JOB.PIS3_LINENO

GO
/****** Object:  View [dbo].[v_infoscntoday_wip]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_infoscntoday_wip]
AS
SELECT        a.ITH_ITMCD, b.MITM_ITMD1, a.ITH_DOC, a.ITH_QTY, b.MITM_STKUOM, c.MSTEMP_FNM, a.ITH_LUPDT, a.ITH_SER, COALESCE(SER_RMRK,'') SER_RMRK
FROM            dbo.ITH_TBL AS a INNER JOIN
                         dbo.MITM_TBL AS b ON a.ITH_ITMCD = b.MITM_ITMCD INNER JOIN
                         dbo.MSTEMP_TBL AS c ON a.ITH_USRID = c.MSTEMP_ID
						 INNER JOIN SER_TBL ON ITH_SER=SER_ID
WHERE        (a.ITH_DATE = CONVERT(date, GETDATE())) AND (a.ITH_FORM = 'INC') AND ITH_WH='AWIP1'
GO
/****** Object:  View [dbo].[V_SPLSCN_TBLC]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[V_SPLSCN_TBLC] AS
SELECT *, 
CASE WHEN convert(varchar(10), SPLSCN_LUPDT, 108) < '07:00:00'  
	THEN DATEADD(DAY,-1,CONVERT(DATE,SPLSCN_LUPDT)) 
ELSE convert(date,SPLSCN_LUPDT) 
END SPLSCN_DATE 
FROM SPLSCN_TBL 
GO
/****** Object:  View [dbo].[VPARTREQSTATUS]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[VPARTREQSTATUS] AS
SELECT VMAIN.SPL_DOC
,CASE WHEN ISNULL(VAL_CHIP,0) = 0  THEN 'O' 
	WHEN ISNULL(VAL_CHIP,0) >0 AND ISNULL(VAL_CHIP,0) = ISNULL(ROW_CHIP,0)  THEN 'X'
	WHEN ISNULL(VAL_CHIP,0) >0 AND ISNULL(VAL_CHIP,0) != ISNULL(ROW_CHIP,0) THEN 'T'
	ELSE '-'
	END STSCHIP
,SPL_BG
FROM
(
SELECT VX.SPL_DOC
,SUM(CASE WHEN VX.SPL_CAT = '_' THEN 1 END) VAL_CHIP
,SPL_BG
FROM
	(SELECT V1.*,ISNULL(SUPQT,0) SUPQT FROM
			(SELECT SPL_DOC,SPL_LINE,SPL_FEDR,SPL_ORDERNO,SPL_CAT,SPL_ITMCD,SUM(SPL_QTYREQ) REQQT ,max(SPL_BG) SPL_BG
				FROM SPL_TBL 	WHERE SPL_DOC LIKE 'PR-%'		
				GROUP BY SPL_DOC,SPL_LINE,SPL_FEDR,SPL_ORDERNO,SPL_CAT,SPL_ITMCD
			) V1
			LEFT JOIN 
			(SELECT SPLSCN_DOC,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ORDERNO,SPLSCN_CAT,SPLSCN_ITMCD,SUM(SPLSCN_QTY) SUPQT 
				FROM SPLSCN_TBL        
				GROUP BY SPLSCN_DOC,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ORDERNO,SPLSCN_CAT,SPLSCN_ITMCD
			) V2 ON SPL_DOC=SPLSCN_DOC AND SPL_LINE=SPLSCN_LINE AND SPL_FEDR=SPLSCN_FEDR AND SPL_CAT=SPLSCN_CAT AND SPL_ITMCD=SPLSCN_ITMCD and SPL_ORDERNO=SPLSCN_ORDERNO			
		WHERE REQQT>ISNULL(SUPQT,0)
	) VX
GROUP BY VX.SPL_DOC,SPL_BG
) VMAIN
LEFT JOIN
(
	SELECT SPL_DOC ,SUM(CASE WHEN SPL_CAT = '_' THEN 1 END) ROW_CHIP	
	FROM 
		(SELECT SPL_DOC,SPL_CAT,SPL_FEDR,SPL_ORDERNO,SPL_ITMCD FROM SPL_TBL
		GROUP BY SPL_DOC,SPL_CAT,SPL_FEDR,SPL_ORDERNO,SPL_ITMCD) VGRUP
	GROUP BY SPL_DOC
) VREQ ON VMAIN.SPL_DOC=VREQ.SPL_DOC
GO
/****** Object:  View [dbo].[v_releasedrm]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[v_releasedrm]
as
SELECT ITH_DOC, ITH_ITMCD,ITH_REMARK, SUM(ITH_QTY) ITH_QTY FROM ITH_TBL WHERE ITH_DOC LIKE '%PND%' AND ITH_QTY >0  AND ITH_FORM='TRFIN-RM' 
group by ITH_DOC, ITH_ITMCD,ITH_REMARK
GO
/****** Object:  View [dbo].[v_spl]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


--SELECT * FROM SPLSCN_TBL WHERE SPLSCN_DOC='SP-MAT-2019-12-0050'
--CREATE VIEW v_splscn as
--SELECT CONCAT(SPLSCN_DOC,'|',SPLSCN_LINE,'|',SPLSCN_CAT,'|', SPLSCN_FEDR) THSCN_DOC, SPLSCN_ITMCD, SPLSCN_QTY
--FROM SPLSCN_TBL 
--WHERE SPLSCN_DOC='SP-MAT-2019-12-0050'
CREATE VIEW [dbo].[v_spl] as
SELECT CONCAT(RTRIM(SPL_DOC),'|',RTRIM(SPL_LINE),'|',RTRIM(SPL_CAT),'|', RTRIM(SPL_FEDR)) TH_DOC, SPL_ITMCD, SPL_QTYREQ FROM SPL_TBL
--select * from v_spl a 
--group by THSCN

GO
/****** Object:  Table [dbo].[RETSCN_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RETSCN_TBL](
	[RETSCN_ID] [varchar](50) NOT NULL,
	[RETSCN_SPLDOC] [varchar](50) NULL,
	[RETSCN_CAT] [varchar](50) NULL,
	[RETSCN_LINE] [varchar](50) NULL,
	[RETSCN_FEDR] [varchar](50) NULL,
	[RETSCN_ORDERNO] [varchar](50) NULL,
	[RETSCN_ITMCD] [varchar](50) NULL,
	[RETSCN_LOT] [varchar](50) NULL,
	[RETSCN_QTYBEF] [decimal](12, 3) NULL,
	[RETSCN_QTYAFT] [decimal](12, 3) NULL,
	[RETSCN_CNTRYID] [varchar](2) NULL,
	[RETSCN_ROHS] [varchar](1) NOT NULL,
	[RETSCN_SAVED] [char](1) NULL,
	[RETSCN_HOLD] [char](1) NULL,
	[RETSCN_RMRK] [varchar](25) NULL,
	[RETSCN_LUPDT] [datetime] NULL,
	[RETSCN_USRID] [varchar](50) NULL,
	[RETSCN_PC] [varchar](25) NULL,
	[RETSCN_CNFRMDT] [date] NULL,
	[RETSCN_UNIQUEKEY] [varchar](21) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
 CONSTRAINT [PK_RETSCN_TBL] PRIMARY KEY CLUSTERED 
(
	[RETSCN_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[V_RETSCN_TBLC]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE view [dbo].[V_RETSCN_TBLC] AS
SELECT
  *,
  CASE
    WHEN convert(varchar(10), RETSCN_LUPDT, 108) < '07:00:00' THEN DATEADD(DAY, -1, CONVERT(DATE, RETSCN_LUPDT))
    ELSE convert(date, RETSCN_LUPDT)
  END RETSCN_DATE
FROM
  RETSCN_TBL
GO
/****** Object:  Table [dbo].[SCR_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SCR_TBL](
	[SCR_DOC] [varchar](50) NULL,
	[SCR_DT] [date] NULL,
	[SCR_ITMCD] [varchar](50) NULL,
	[SCR_ITMLOT] [varchar](50) NULL,
	[SCR_QTY] [decimal](12, 3) NULL,
	[SCR_REMARK] [varchar](50) NULL,
	[SCR_REFFDOC] [varchar](50) NULL,
	[SCR_LUPDT] [datetime] NULL,
	[SCR_USRID] [varchar](9) NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[v_scraprm]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_scraprm] as
SELECT SCR_REFFDOC,SCR_ITMCD,SCR_ITMLOT,SUM(SCR_QTY) SCR_QTY FROM SCR_TBL
GROUP BY SCR_REFFDOC,SCR_ITMCD,SCR_ITMLOT
GO
/****** Object:  View [dbo].[v_splscn]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[v_splscn] as
SELECT CONCAT(SPLSCN_DOC,'|',SPLSCN_LINE,'|',SPLSCN_CAT,'|', SPLSCN_FEDR) THSCN_DOC, SPLSCN_ITMCD, SPLSCN_QTY, SPLSCN_SAVED
FROM SPLSCN_TBL 

GO
/****** Object:  View [dbo].[v_splret]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE VIEW [dbo].[v_splret] as
SELECT CONCAT(RETSCN_SPLDOC,'|',RETSCN_LINE,'|',RETSCN_CAT,'|', RETSCN_FEDR) THSCN_DOC, RETSCN_ITMCD, RETSCN_QTYAFT
FROM RETSCN_TBL 


GO
/****** Object:  View [dbo].[wms_v_ready_to_book_spl_base]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[wms_v_ready_to_book_spl_base] as
SELECT VREQ.*,SUPQT,RTRIM(MITM_ITMD1) ITMD1, REQQT-ISNULL(SUPQT,0) BALQT FROM
(select SPL_DOC,SPL_CAT ,SPL_ITMCD,SUM(SPL_QTYREQ) REQQT from SPL_TBL
where SPL_DOC like 'SP-IEI%' and SPL_CAT in ('PCB','SP') 
GROUP BY SPL_DOC ,SPL_ITMCD,SPL_CAT) VREQ
LEFT JOIN (
SELECT SPLSCN_DOC,SPLSCN_CAT,SPLSCN_ITMCD,SUM(SPLSCN_QTY) SUPQT FROM SPLSCN_TBL WHERE SPLSCN_DOC LIKE 'SP-IEI%' AND SPLSCN_CAT IN ('PCB','SP')
GROUP BY SPLSCN_DOC,SPLSCN_ITMCD,SPLSCN_CAT
) VACT ON SPL_DOC=SPLSCN_DOC AND SPL_ITMCD=SPLSCN_ITMCD AND SPL_CAT=SPLSCN_CAT
LEFT JOIN MITM_TBL ON SPL_ITMCD=MITM_ITMCD
WHERE ISNULL(SUPQT,0)<REQQT
GO
/****** Object:  View [dbo].[v_jobinfo]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
Create view [dbo].[v_jobinfo] as
select SPL_JOBNO,SPL_FG, SPL_CUSCD, SPL_LOTSZ from SPL_TBL
GROUP BY SPL_JOBNO,SPL_FG, SPL_CUSCD,SPL_LOTSZ
GO
/****** Object:  View [dbo].[v_infoscntoday_qa]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_infoscntoday_qa]
AS
SELECT        a.ITH_ITMCD, b.MITM_ITMD1, a.ITH_DOC, a.ITH_QTY, b.MITM_STKUOM, c.MSTEMP_FNM, a.ITH_LUPDT, a.ITH_SER, COALESCE(SER_RMRK,'') SER_RMRK
FROM            dbo.ITH_TBL AS a INNER JOIN
                         dbo.MITM_TBL AS b ON a.ITH_ITMCD = b.MITM_ITMCD INNER JOIN
                         dbo.MSTEMP_TBL AS c ON a.ITH_USRID = c.MSTEMP_ID
						 INNER JOIN SER_TBL ON ITH_SER=SER_ID
WHERE        (a.ITH_DATE = CONVERT(date, GETDATE())) AND (a.ITH_FORM = 'INC-QA-FG')
GO
/****** Object:  View [dbo].[vinitlocation]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[vinitlocation] AS
select MSTLOC_TBL.*, 
CASE 
	WHEN MSTLOC_RACK='A' THEN 1 
	WHEN MSTLOC_RACK='B' THEN 2
	WHEN MSTLOC_RACK='C' THEN 3
	WHEN MSTLOC_RACK='D' THEN 4
	WHEN MSTLOC_RACK='E' THEN 5
	WHEN MSTLOC_RACK='F' THEN 6
	WHEN MSTLOC_RACK='G' THEN 7
	WHEN MSTLOC_RACK='H' THEN 8
	WHEN MSTLOC_RACK='I' THEN 9
	WHEN MSTLOC_RACK='J' THEN 10
	WHEN MSTLOC_RACK='K' THEN 11
	WHEN MSTLOC_RACK='L' THEN 12
	WHEN MSTLOC_RACK='M' THEN 13
	WHEN MSTLOC_RACK='N' THEN 14
	WHEN MSTLOC_RACK='O' THEN 15
	WHEN MSTLOC_RACK='P' THEN 16
	WHEN MSTLOC_RACK='Q' THEN 17
	WHEN MSTLOC_RACK='R' THEN 18
	WHEN MSTLOC_RACK='S' THEN 19
	WHEN MSTLOC_RACK='T' THEN 20
	WHEN MSTLOC_RACK='U' THEN 21
	WHEN MSTLOC_RACK='V' THEN 22
	WHEN MSTLOC_RACK='W' THEN 23
	WHEN MSTLOC_RACK='X' THEN 24
	WHEN MSTLOC_RACK='Y' THEN 25
	WHEN MSTLOC_RACK='Z' THEN 26
	WHEN MSTLOC_RACK='AA' THEN 27
	WHEN MSTLOC_RACK='AB' THEN 28
	WHEN MSTLOC_RACK='AC' THEN 29
	WHEN MSTLOC_RACK='AD' THEN 30
	WHEN MSTLOC_RACK='AE' THEN 31
	WHEN MSTLOC_RACK='AF' THEN 32
	WHEN MSTLOC_RACK='AG' THEN 33
	WHEN MSTLOC_RACK='AH' THEN 34	
END aliasrack ,ITMLOC_BG
from MSTLOC_TBL
LEFT JOIN ITMLOC_TBL ON MSTLOC_CD=ITMLOC_LOC

GO
/****** Object:  Table [dbo].[RLSSER_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RLSSER_TBL](
	[RLSSER_DOC] [varchar](50) NOT NULL,
	[RLSSER_SER] [varchar](21) NOT NULL,
	[RLSSER_DT] [date] NULL,
	[RLSSER_QTY] [decimal](12, 3) NULL,
	[RLSSER_SCANNED] [char](1) NULL,
	[RLSSER_SAVED] [char](1) NULL,
	[RLSSER_REFFDOC] [varchar](50) NULL,
	[RLSSER_REMARK] [varchar](50) NULL,
	[RLSSER_REFFSER] [varchar](50) NULL,
	[RLSSER_LUPDT] [datetime] NULL,
	[RLSSER_USRID] [varchar](50) NULL,
 CONSTRAINT [PK__RLSSER_T__3C71F64B2A0C7476] PRIMARY KEY CLUSTERED 
(
	[RLSSER_DOC] ASC,
	[RLSSER_SER] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[v_releasedfg]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[v_releasedfg] as
--SELECT ITH_DOC, ITH_ITMCD,ITH_SER, SUM(ITH_QTY) ITH_QTY FROM ITH_TBL 
--WHERE ITH_DOC LIKE '%PNDS%' AND ITH_QTY >0  AND ITH_FORM='TRFIN-FG' 
--group by ITH_DOC, ITH_ITMCD, ITH_SER

SELECT RLSSER_REFFDOC, RLSSER_REFFSER, SUM(RLSSER_QTY) RLSQTY FROM RLSSER_TBL
GROUP BY RLSSER_REFFDOC, RLSSER_REFFSER

GO
/****** Object:  View [dbo].[v_infoscntoday_wh]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_infoscntoday_wh]
AS
SELECT        a.ITH_ITMCD, b.MITM_ITMD1, a.ITH_DOC, a.ITH_QTY, b.MITM_STKUOM, c.MSTEMP_FNM, a.ITH_LUPDT, a.ITH_SER
FROM            dbo.ITH_TBL AS a INNER JOIN
                         dbo.MITM_TBL AS b ON a.ITH_ITMCD = b.MITM_ITMCD INNER JOIN
                         dbo.MSTEMP_TBL AS c ON a.ITH_USRID = c.MSTEMP_ID
WHERE        (a.ITH_DATE = CONVERT(date, GETDATE())) AND (a.ITH_FORM = 'INC-WH-FG')
GO
/****** Object:  View [dbo].[v4p_splhead]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v4p_splhead] as
select DISTINCT SPL_DOC,SPL_CAT,SPL_LINE,SPL_FEDR FROM SPL_TBL
GO
/****** Object:  Table [dbo].[SPLBOOK_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SPLBOOK_TBL](
	[SPLBOOK_DOC] [varchar](15) NOT NULL,
	[SPLBOOK_SPLDOC] [varchar](45) NOT NULL,
	[SPLBOOK_CAT] [varchar](10) NULL,
	[SPLBOOK_ITMCD] [varchar](40) NOT NULL,
	[SPLBOOK_QTY] [decimal](12, 3) NOT NULL,
	[SPLBOOK_DATE] [date] NOT NULL,
	[SPLBOOK_LINE] [int] NOT NULL,
	[SPLBOOK_CLOSEDDT] [datetime] NULL,
	[SPLBOOK_LUPDTD] [datetime] NULL,
	[SPLBOOK_USRID] [varchar](10) NOT NULL,
 CONSTRAINT [PK__SPLBOOK___8AC7DAD57F510D96] PRIMARY KEY CLUSTERED 
(
	[SPLBOOK_DOC] ASC,
	[SPLBOOK_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[wms_v_book_ost]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



--select * from SPLBOOK_TBL 
--where SPLBOOK_DOC='B223161'

CREATE VIEW [dbo].[wms_v_book_ost] as
select SPLBOOK_DOC,SPLBOOK_SPLDOC,SPLBOOK_CAT,SPLBOOK_ITMCD,max(SPLBOOK_QTY) RQT,ISNULL(SUM(SPLSCN_QTY),0) AQT,RTRIM(MITM_ITMD1) ITMD1,MAX(SPLBOOK_DATE) BOOKDT from SPLBOOK_TBL 
LEFT JOIN SPLSCN_TBL
ON SPLBOOK_SPLDOC=SPLSCN_DOC AND SPLBOOK_ITMCD=SPLSCN_ITMCD AND SPLSCN_LUPDT>SPLBOOK_LUPDTD
LEFT JOIN MITM_TBL ON SPLBOOK_ITMCD=MITM_ITMCD
group by SPLBOOK_DOC,SPLBOOK_SPLDOC,SPLBOOK_CAT,SPLBOOK_ITMCD,MITM_ITMD1
having max(SPLBOOK_QTY)>ISNULL(SUM(SPLSCN_QTY),0)




--INSERT INTO SPLSCN_TBL VALUES
--('20220117220129900',	'SP-IEI-2022-01-0703',	'PCB',	'SMT-D1',	'F',	'TBL1-28',	'220937800',	'12345',	'1',	'1',	'2022-03-16 10:51:27.740',	'1210013',	NULL)
--select * from SPLSCN_TBL where SPLSCN_DOC='SP-IEI-2022-01-0703' and SPLSCN_ITMCD='220937800'
GO
/****** Object:  Table [dbo].[RCVNI_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RCVNI_TBL](
	[RCVNI_LINE] [int] NOT NULL,
	[RCVNI_PO] [varchar](12) NOT NULL,
	[RCVNI_DO] [varchar](50) NOT NULL,
	[RCVNI_ITMNM] [varchar](150) NOT NULL,
	[RCVNI_QTY] [decimal](12, 3) NOT NULL,
	[RCVNI_CRTDBY] [varchar](50) NULL,
	[RCVNI_CRTDAT] [datetime] NULL,
	[RCVNI_LUPDTBY] [varchar](50) NULL,
	[RCVNI_LUPDTAT] [datetime] NULL,
	[RCVNI_RCVDATE] [date] NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[wms_v_receiving_list]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[wms_v_receiving_list]
AS
SELECT VRCV.*
	,POSUBJECT
	,PODEPT
	,PO_VAT
FROM (
	SELECT RCV_BCTYPE
		,RCV_RPNO
		,RCV_BCNO
		,RCV_RPDATE
		,MAX(RCV_GRLNO) RECEIVINGNO
		,RCV_RCVDATE
		,RCV_PO
		,RCV_SUPCD
		,RTRIM(MSUP_SUPNM) SUPNM
		,RTRIM(MITM_ITMCD) ITEMCODE
		,RTRIM(MITM_ITMD1) ITEMNAME
		,SUM(RCV_QTY) RQT
		,RTRIM(MITM_STKUOM) UOM
		,RTRIM(MSUP_SUPCR) MSUP_SUPCR
		,RCV_PRPRC
		,SUM(RCV_QTY) * RCV_PRPRC AMOUNT
	FROM RCV_TBL
	LEFT JOIN MITM_TBL ON RCV_ITMCD = MITM_ITMCD
	LEFT JOIN MSUP_TBL ON RCV_SUPCD = MSUP_SUPCD
	WHERE MITM_MODEL NOT IN (
			'1'			
			)
			AND RCV_GRLNO NOT LIKE '%GRL%'
			AND RCV_PO!='-'
			AND RCV_WH NOT IN ('AFWH3RT')
	GROUP BY RCV_BCTYPE
		,RCV_RPNO
		,RCV_BCNO
		,RCV_RPDATE
		,RCV_RCVDATE
		,RCV_PO
		,RCV_SUPCD
		,MSUP_SUPNM
		,MITM_ITMCD
		,MITM_ITMD1
		,MITM_STKUOM
		,MSUP_SUPCR
		,RCV_PRPRC
	) VRCV
LEFT JOIN (
	SELECT PO_NO
		,MAX(PO_SUBJECT) POSUBJECT
		,MAX(PO_DEPT) PODEPT
		,MAX(PO_VAT) PO_VAT
	FROM PO_TBL
	GROUP BY PO_NO
	) VPO ON RCV_PO = PO_NO

UNION

SELECT '' RCV_BCTYPE
	,'' RCV_RPNO
	,'' RCV_BCNO
	,RCVNI_RCVDATE RCV_RPDATE
	,RCVNI_LINE RECEIVINGNO
	,RCVNI_RCVDATE RCV_RCVDATE
	,RCVNI_PO RCV_PO
	,PO_SUPCD RCV_SUPCD
	,RTRIM(MSUP_SUPNM) SUPNM
	,'' ITEMCODE
	,RCVNI_ITMNM ITEMNAME
	,SUM(RCVNI_QTY) RQT
	,UM UOM
	,RTRIM(MSUP_SUPCR) MSUP_SUPCR
	,PO_PRICE - (PO_PRICE * PO_DISC / 100) RCV_PRPRC
	,SUM(RCVNI_QTY) * (PO_PRICE - (PO_PRICE * PO_DISC / 100)) AMOUNT
	,POSUBJECT
	,PODEPT
	,PO_VAT
FROM RCVNI_TBL
LEFT JOIN (
	SELECT PO_NO
		,MAX(PO_SUPCD) PO_SUPCD
		,PO_ITMNM
		,MAX(PO_UM) UM
		,max(PO_PRICE) PO_PRICE
		,max(PO_DISC) PO_DISC
		,MAX(PO_SUBJECT) POSUBJECT
		,MAX(PO_DEPT) PODEPT
		,MAX(PO_VAT) PO_VAT
	FROM PO_TBL
	GROUP BY PO_NO
		,PO_ITMNM
	) VPO ON RCVNI_PO = PO_NO
	AND RCVNI_ITMNM = PO_ITMNM
LEFT JOIN MSUP_TBL ON PO_SUPCD = MSUP_SUPCD
GROUP BY RCVNI_RCVDATE
	,RCVNI_LINE
	,RCVNI_RCVDATE
	,RCVNI_PO
	,PO_SUPCD
	,MSUP_SUPNM
	,RCVNI_ITMNM
	,UM
	,MSUP_SUPCR
	,PO_PRICE
	,PO_DISC
	,POSUBJECT
	,PODEPT
	,PO_VAT
GO
/****** Object:  Table [dbo].[SERML_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SERML_TBL](
	[SERML_NEWID] [varchar](21) NOT NULL,
	[SERML_COMID] [varchar](21) NOT NULL,
	[SERML_USRID] [varchar](50) NULL,
	[SERML_LUPDT] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[SERML_NEWID] ASC,
	[SERML_COMID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[v_stock_smt]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[v_stock_smt] AS
SELECT
	ITH_ITMCD,
	SUM(STKQTY) AS STKQTY,
	ITH_WH
FROM
	(
	select
		ITH_ITMCD,
		sum(ITH_QTY) STKQTY,
		ITH_WH
	from
		v_ith_tblc
	where
		ITH_DATEC <= '2021-05-01'
		AND (ITH_WH = 'ARWH1'
		OR ITH_WH = 'ARWH2'
		OR ITH_WH = 'NRWH2')
	GROUP BY
		ITH_ITMCD,
		ITH_WH
	HAVING
		SUM(ITH_QTY) > 0
UNION
	SELECT
		SERD2_ITMCD AS ITH_ITMCD,
		SUM(SERD2_QTY) STKQTY,
		ITH_WH
	FROM
		(
		select
			ITH_SER,
			sum(ITH_QTY) STKQTY,
			ITH_WH
		from
			v_ith_tblc
		where
			ITH_DATEC <= '2021-05-01'
			AND ITH_WH IN ('AFWH3',
			'ARSHP')
		GROUP BY
			ITH_SER,
			ITH_WH
		HAVING
			SUM(ITH_QTY) >0) VFG
	LEFT JOIN SERD2_TBL ON
		ITH_SER = SERD2_SER
	LEFT JOIN MITM_TBL ON
		SERD2_ITMCD = MITM_ITMCD
	WHERE
		MITM_MODEL = '0'
	GROUP BY
		SERD2_ITMCD,
		ITH_WH
	HAVING
		SUM(STKQTY) > 0
UNION
	SELECT
		SERD2_ITMCD AS ITH_ITMCD,
		SUM(SERD2_QTY) STKQTY,
		ITH_WH
	FROM
		(
		select
			ITH_SER,
			sum(ITH_QTY) STKQTY,
			ITH_WH
		from
			v_ith_tblc
		where
			ITH_DATEC <= '2021-05-01'
			AND ITH_WH IN ('AFWH3',
			'ARSHP')
		GROUP BY
			ITH_SER,
			ITH_WH
		HAVING
			SUM(ITH_QTY) >0) VFG
	INNER JOIN SERML_TBL ON
		ITH_SER = SERML_NEWID
	LEFT JOIN SERD2_TBL ON
		SERML_COMID = SERD2_SER
	GROUP BY
		SERD2_ITMCD,
		ITH_WH ) A
GROUP BY
	ITH_ITMCD,
	ITH_WH
GO
/****** Object:  Table [dbo].[DLVSO_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[DLVSO_TBL](
	[DLVSO_DLVID] [varchar](50) NULL,
	[DLVSO_ITMCD] [varchar](50) NULL,
	[DLVSO_QTY] [int] NULL,
	[DLVSO_CPONO] [varchar](50) NULL,
	[DLVSO_PRICE] [numeric](15, 6) NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[wms_v_soplot_vs_dlv]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[wms_v_soplot_vs_dlv] as
SELECT VDELVSO.*,SER_ITMID,TGLPEN,DLVQTY FROM
(select DLVSO_CPONO,DLVSO_ITMCD,DLVSO_DLVID,sum(DLVSO_QTY) PLOTQTY from DLVSO_TBL group by
DLVSO_DLVID,DLVSO_ITMCD,DLVSO_CPONO) VDELVSO
LEFT JOIN (
SELECT DLV_ID,SER_ITMID,SUM(DLV_QTY) DLVQTY,ISNULL(MAX(DLV_RPDATE),MAX(DLV_DATE)) TGLPEN FROM DLV_TBL LEFT JOIN SER_TBL ON DLV_SER=SER_ID
GROUP BY DLV_ID,SER_ITMID) VPLOTSO ON DLVSO_ITMCD=SER_ITMID AND DLVSO_DLVID=DLV_ID
GO
/****** Object:  Table [dbo].[USRLOG_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[USRLOG_TBL](
	[USRLOG_ID] [varchar](20) NOT NULL,
	[USRLOG_USR] [varchar](9) NULL,
	[USRLOG_GRP] [varchar](10) NULL,
	[USRLOG_TYP] [varchar](5) NULL,
	[USRLOG_TM] [datetime] NOT NULL,
	[USRLOG_IP] [varchar](15) NULL,
	[USRLOG_MENU] [varchar](50) NULL,
	[USRLOG_URL] [varchar](100) NULL,
 CONSTRAINT [PK__USRLOG_T__223D6C039117A9F7] PRIMARY KEY CLUSTERED 
(
	[USRLOG_ID] ASC,
	[USRLOG_TM] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[v_lastidlgusr]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE VIEW [dbo].[v_lastidlgusr] AS
--SELECT TOP 1 right(USRLOG_ID, 3) AS lgusrs
--   FROM USRLOG_TBL
--  WHERE YEAR(USRLOG_TM) = YEAR(getdate()) 
--  AND MONTH(USRLOG_TM) = MONTH(getdate())
--  ORDER BY (right(USRLOG_ID, 3)) DESC;


--SELECT TOP 1 substring(USRLOG_ID,8, 10) AS lgusrs
--   FROM USRLOG_TBL
--  WHERE convert(date,USRLOG_TM) = convert(date,getdate()) 
  
--  ORDER BY 1 DESC;

SELECT TOP 1 convert(int,substring(USRLOG_ID,8, 10)) AS lgusrs
   FROM USRLOG_TBL
  WHERE convert(date,USRLOG_TM) = convert(date,getdate()) 
  
  ORDER BY 1 DESC;
	
GO
/****** Object:  Table [dbo].[SO_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SO_TBL](
	[SO_NO] [varchar](50) NULL,
	[SO_ORDRDT] [date] NULL,
	[SO_ORDRQT] [int] NULL,
	[SO_ITEMCD] [varchar](50) NULL,
	[SO_BG] [varchar](25) NULL,
	[SO_CUSCD] [varchar](25) NULL,
	[SO_DELCD] [varchar](15) NULL,
	[SO_DELSCH] [date] NULL,
	[SO_LINENO] [varchar](50) NULL,
	[SO_LINE] [int] NULL,
	[SO_LUPDT] [datetime] NULL,
	[SO_USRID] [varchar](30) NULL
) ON [PRIMARY]
GO
/****** Object:  View [dbo].[V_SO_OST]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE VIEW [dbo].[V_SO_OST] AS
SELECT V1.*,ISNULL(TTLUSEQT,0) TTLUSEQT, (ORDQT-ISNULL(TTLUSEQT,0)) BALQT  FROM
(select SO_NO,SO_BG,SO_CUSCD,SO_ITEMCD,SUM(SO_ORDRQT) ORDQT,SO_DELCD,MAX(SO_ORDRDT) SO_ORDRDT from SO_TBL
GROUP BY SO_NO,SO_BG,SO_CUSCD,SO_ITEMCD,SO_DELCD) V1
LEFT JOIN 
(
	SELECT DLVSO_CPONO,DLVSO_ITMCD,SUM(DLVSOQT) TTLUSEQT,DLV_BSGRP,DLV_CUSTCD,DLV_CONSIGN FROM
	(SELECT DLV_ID,DLV_BSGRP, DLV_CUSTCD, DLV_CONSIGN FROM DLV_TBL
	GROUP BY DLV_ID,DLV_BSGRP, DLV_CUSTCD, DLV_CONSIGN) VDELV
	INNER JOIN
	(
	SELECT DLVSO_DLVID,DLVSO_CPONO,DLVSO_ITMCD, SUM(DLVSO_QTY) DLVSOQT FROM DLVSO_TBL GROUP BY DLVSO_DLVID, DLVSO_ITMCD,DLVSO_CPONO
	) VDELVSO ON DLV_ID=DLVSO_DLVID
	GROUP BY DLVSO_CPONO,DLVSO_ITMCD,DLV_BSGRP,DLV_CUSTCD,DLV_CONSIGN
) V2 ON SO_NO=DLVSO_CPONO AND SO_ITEMCD=DLVSO_ITMCD and SO_BG=DLV_BSGRP AND SO_CUSCD=DLV_CUSTCD AND SO_DELCD=DLV_CONSIGN
WHERE ORDQT!=ISNULL(TTLUSEQT,0)


GO
/****** Object:  View [dbo].[v_lastidlgusrplus]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[v_lastidlgusrplus]
AS
--SELECT        CASE WHEN
--                             ((SELECT        v_lastidlgusr.lgusrs
--                                 FROM            v_lastidlgusr)) IS NULL THEN concat('L', year(getdate()), RIGHT(concat('0', month(getdate())), 2), '1') 
--								 ELSE concat('L', year(getdate()), RIGHT(concat('0', month(getdate())), 2), RIGHT(concat('000', CAST
--									 ((SELECT        lgusrs
--										 FROM            v_lastidlgusr) AS int) + 1), 3)) END AS idnew
  SELECT        CASE WHEN
                             ((SELECT        v_lastidlgusr.lgusrs
                                 FROM            v_lastidlgusr)) IS NULL THEN concat('L', year(getdate()), RIGHT(concat('0', month(getdate())), 2), '1') 
								 ELSE concat('L', year(getdate()), RIGHT(concat('0', month(getdate())), 2),  CAST
									 ((SELECT        lgusrs
										 FROM            v_lastidlgusr) AS int) + 1) END AS idnew
GO
/****** Object:  View [dbo].[XFTRN_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE view [dbo].[XFTRN_TBL] AS
select *,CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE -1*FTRN_TRNQT END IOQT from SRVMEGA.PSI_MEGAEMS.dbo.FTRN_TBL  
GO
/****** Object:  View [dbo].[v_mitm_bsgroup]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE view [dbo].[v_mitm_bsgroup] as
--select PDPP_MDLCD, PDPP_BSGRP from XWO WHERE PDPP_GRNQT>0
--group by PDPP_MDLCD, PDPP_BSGRP

select FTRN_ITMCD PDPP_MDLCD, FTRN_BSGRP PDPP_BSGRP from (SELECT FTRN_BSGRP, FTRN_ITMCD FROM XFTRN_TBL
GROUP BY FTRN_BSGRP, FTRN_ITMCD ) V1 
union
	(
	select SSO2_MDLCD PDPP_MDLCD, SSO2_BSGRP PDPP_BSGRP from XSSO2 group by SSO2_BSGRP, SSO2_MDLCD
	)
GO
/****** Object:  View [dbo].[ENG_BOMSTX]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[ENG_BOMSTX] AS
SELECT *
FROM SMT_ENG.dbo.BOMSTX_TBL A
GO
/****** Object:  View [dbo].[ENG_COMM_SUB_PART]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[ENG_COMM_SUB_PART]
AS
SELECT
  'COMMON PART' AS [TYPE],
  '' AS [ASSY CODE],
  A.ITMCDPRI AS [MAIN PARTS],
  A.ITMCDALT AS [ALTERNATIVE PARTS],
  A.ITMDSALT AS [DESCRIPTION],
  B.CAT AS [CATEGORY],
  B.SUPNMALT AS [ALT SUPP NAME],
  B.APPROVED
FROM
  SMT_ENG.dbo.COMMPRT_LST A
  LEFT JOIN SMT_ENG.dbo.COMMPRT B ON A.ITMCDPRI_ORI = B.ITMCDPRI
  AND A.ITMCDALT_ORI = B.ITMCDALT
  AND A.CPTYPE = B.CPTYPE
WHERE
  A.CPTYPE IN ('G', 'S')
  AND B.CPTYPE IN ('G', 'S')
  AND B.APPROVED='1'
--   AND A.ITMCDPRI = '211462900'
UNION
SELECT
  'SUBSTITUTE' AS [TYPE],
  USEDBY AS [ASSY CODE],
  PRTCD AS [MAIN PARTS],
  SUBCD AS [ALTERNATIVE PARTS],
  MNFCD AS [DESCRIPTION],
  SUBNM AS [CATEGORY],
  SUPNM AS [ALT SUPP NAME],
  CAST(1 AS BIT) AS APPROVED
FROM
  SMT_ENG.dbo.TECPRTSUB
-- WHERE
--   REPLACE(PRTCD, '-', '') = '211462900'


GO
/****** Object:  View [dbo].[ENG_COMMPRT_LST]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[ENG_COMMPRT_LST] AS
select * from SMT_ENG.dbo.COMMPRT_LST
GO
/****** Object:  View [dbo].[ENG_STXSPL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[ENG_STXSPL] AS
SELECT * FROM SMT_ENG.dbo.STXSPL
GO
/****** Object:  View [dbo].[ENG_TECPRT]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[ENG_TECPRT] AS
SELECT
  REPLACE(ITMCD, '-', '') ITMCD,
  A.ITMNM
FROM
  SMT_ENG.dbo.TECPRT A
GROUP BY
  A.ITMCD,
  A.ITMNM
GO
/****** Object:  View [dbo].[ENG_TECPRTLC]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[ENG_TECPRTLC] AS
SELECT
  REPLACE(ITMCD, '-', '') PRTCD,
  A.STDMAX,
  A.STDMIN,
  A.MEAS
FROM
  SMT_ENG.dbo.MITMSPCS A
WHERE
  A.STDMAX != 0
  AND A.STDMIN != 0
GROUP BY
  A.ITMCD,
  A.STDMAX,
  A.STDMIN,
  A.MEAS
GO
/****** Object:  View [dbo].[ENG_TECPRTSUB]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[ENG_TECPRTSUB] AS
select replace(ITMCD,'-','') ITMCD, REPLACE(PRTCD,'-','') PRTCD, REPLACE(SUBCD,'-','') SUBCD from SMT_ENG.dbo.TECPRTSUB
where ITMCD!=''
GO
/****** Object:  View [dbo].[VCIMS_MBLA_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[VCIMS_MBLA_TBL] AS
select * from SRVCIMS.CIM_WMS.dbo.MBLA_TBL
GO
/****** Object:  View [dbo].[VCIMS_MBO1_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[VCIMS_MBO1_TBL] AS
select * from SRVCIMS.CIM_WMS.dbo.MBO1_TBL
GO
/****** Object:  View [dbo].[VCIMS_MBO2_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[VCIMS_MBO2_TBL] AS
select * from SRVCIMS.CIM_WMS.dbo.MBO2_TBL
GO
/****** Object:  View [dbo].[VCIMS_TWOR_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[VCIMS_TWOR_TBL] AS
SELECT *
FROM SRVCIMS.CIM_WMS.dbo.TWOR_TBL
GO
/****** Object:  View [dbo].[XICYC]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XICYC] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.ICYC_TBL
GO
/****** Object:  View [dbo].[XIGRN_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE view [dbo].[XIGRN_TBL] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.IGRN_TBL
GO
/****** Object:  View [dbo].[XITRN_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE view [dbo].[XITRN_TBL] AS
select *,CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT ELSE -1*ITRN_TRNQT END IOQT
,CASE WHEN ITRN_IOFLG = '1' 
 THEN ITRN_PRICE ELSE -1*ITRN_PRICE END IOPRICE  from SRVMEGA.PSI_MEGAEMS.dbo.ITRN_TBL  
GO
/****** Object:  View [dbo].[XMBO2]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XMBO2] AS
select * from SRVCIMS.CIM_WMS.dbo.MBO2_TBL

GO
/****** Object:  View [dbo].[XMBOMD1H_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create VIEW [dbo].[XMBOMD1H_TBL] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.MBOMD1H_TBL
GO
/****** Object:  View [dbo].[XMBOMD2H_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XMBOMD2H_TBL] 
AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.MBOMD2H_TBL 
GO
/****** Object:  View [dbo].[XMDEL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XMDEL] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.MDEL_TBL
GO
/****** Object:  View [dbo].[XMITM_VCIMS]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XMITM_VCIMS] AS
select * from SRVCIMS.CIM_WMS.dbo.MITM_TBL
GO
/****** Object:  View [dbo].[XMPAL_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--SELECT * FROM XMSIM_TBL
CREATE VIEW [dbo].[XMPAL_TBL] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.MPAL_TBL
--SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.MPAL_TBL WHERE MPAL_MDLCD = '2266364-4'
--SELECT * FROM XMSIM_TBL WHERE MSIM_MDLCD='2266364-4'
GO
/****** Object:  View [dbo].[XMSIM_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--select TOP 1 isnull(SI_WH,'AFWH3') SI_WH from SI_TBL WHERE SI_DOC = 'SI2021022323'

--SELECT * FROM RETFG_TBL WHERE RETFG_DOCNO='5054193322'

CREATE VIEW [dbo].[XMSIM_TBL] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.MSIM_TBL
GO
/****** Object:  View [dbo].[XMSIMD1_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XMSIMD1_TBL]
AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.MSIMD1_TBL 
GO
/****** Object:  View [dbo].[XMSIMD2_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--SELECT * FROM XMSIM_TBL
CREATE VIEW [dbo].[XMSIMD2_TBL] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.MSIMD2_TBL 
GO
/****** Object:  View [dbo].[XMSPR]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XMSPR] AS SELECT * FROM	SRVMEGA.PSI_MEGAEMS.dbo.MSPR_TBL
GO
/****** Object:  View [dbo].[XMWHS_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XMWHS_TBL] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.MWHS_TBL
GO
/****** Object:  View [dbo].[XPFGI_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XPFGI_TBL] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.PFGI_TBL
GO
/****** Object:  View [dbo].[XPGRELED_VIEW]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XPGRELED_VIEW] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.PGRELED_TBL
GO
/****** Object:  View [dbo].[XPGRN_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE VIEW [dbo].[XPGRN_TBL]
AS
SELECT        a.PGRN_LOCCD, RTRIM(a.PGRN_BSGRP) PGRN_BSGRP, a.PGRN_SUPCD, a.PGRN_SUPCR, a.PGRN_SUPNO, a.PGRN_ITMCD, a.PGRN_GRLNO, a.PGRN_GRNTY, a.PGRN_PONO, a.PGRN_POLNO, a.PGRN_CUSCD,  a.PGRN_CURCD, a.PGRN_SPART, 
                         a.PGRN_ICMQT, a.PGRN_RCVDT, a.PGRN_LOTNO, a.PGRN_RCVQT, a.PGRN_ROKQT, a.PGRN_RNGQT, a.PGRN_UPDBL, a.PGRN_TMOBL, a.PGRN_TMCBL, a.PGRN_LOCPC, a.PGRN_PRPRC, a.PGRN_AMT, a.PGRN_XRATE, 
                         a.PGRN_OWNER, a.PGRN_CONFG, a.PGRN_USRID, a.PGRN_LUPDT, a.PGRN_LOCAM, a.PGRN_RQRLSNO, a.PGRN_PRGID, a.PGRN_POUOM, a.PGRN_ICMPOUOMQTY, a.PGRN_RCVPOUOMQTY, a.PGRN_ROKPOUOMQTY, 
                         a.PGRN_RNGPOUOMQTY, a.PGRN_POUOMPRC
FROM            SRVMEGA.PSI_MEGAEMS.dbo.PGRN_TBL a

GO
/****** Object:  View [dbo].[XPIGN]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XPIGN]
AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.PIGN_TBL
GO
/****** Object:  View [dbo].[XPNGR]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XPNGR] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.PNGR_TBL
GO
/****** Object:  View [dbo].[XPPSN2_LOG]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE view [dbo].[XPPSN2_LOG] AS
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.PPSN2_LOG
GO
/****** Object:  View [dbo].[XPPSNA]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create view [dbo].[XPPSNA] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.PPSNA_TBL
GO
/****** Object:  View [dbo].[XPPSNR]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XPPSNR] AS 
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.PPSNR_TBL
GO
/****** Object:  View [dbo].[XPPSNRD1]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XPPSNRD1] AS 
SELECT * FROM SRVMEGA.PSI_MEGAEMS.dbo.PPSNRD1_TBL
GO
/****** Object:  View [dbo].[XSSHP]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XSSHP] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.SSHP_TBL
GO
/****** Object:  View [dbo].[XSSO2_HIS]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

--select * from SRVMEGA.PSI_MEGAEMS.dbo.SCPOD1_TBL where SCPOD1_ORDNO='SME-20-00088105'
--select * from SRVMEGA.PSI_MEGAEMS.dbo.SCPOD2_TBL WHERE SCPOD2_CPONO='SME-20-00088105'
--ORDER BY SCPOD2_MDLCD ASC
CREATE View [dbo].[XSSO2_HIS] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.SSO2_HIS 
GO
/****** Object:  View [dbo].[XSTKTRNS1]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

create VIEW [dbo].[XSTKTRNS1] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.STKTRNS1_TBL
GO
/****** Object:  View [dbo].[XSTXMBLA_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE VIEW [dbo].[XSTXMBLA_TBL]
AS
SELECT * FROM ieisvrsmt01.STXI_LOTDB.dbo.MBLA_TBL

GO
/****** Object:  View [dbo].[XTRD1H]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[XTRD1H] AS
select * from SRVMEGA.PSI_MEGAEMS.dbo.STRD1H_TBL
GO
/****** Object:  View [dbo].[ZRPSCRAP_HIST]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


--select * from ZRPSAL_BCSTOCK where RPSTOCK_REMARK='21/12/04/0003' and RPSTOCK_QTY<0


CREATE view [dbo].[ZRPSCRAP_HIST] AS
select * from PSI_RPCUST.dbo.RPSCRAP_HIST A where A.deleted_at IS NULL
GO
/****** Object:  Table [dbo].[ACCLOCK_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[ACCLOCK_TBL](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[ACCLOCK_DURATION] [int] NULL,
	[ACCLOCK_THRESHOLD] [int] NULL,
	[ACCLOCK_ALLOWADMIN] [char](1) NULL,
	[ACCLOCK_RESETAFTER] [int] NULL,
	[created_at] [datetime] NULL,
	[created_by] [varchar](9) NULL,
	[updated_at] [datetime] NULL,
	[updated_by] [varchar](9) NULL,
	[deleted_at] [datetime] NULL,
	[deleted_by] [varchar](9) NULL,
 CONSTRAINT [PK_ACCLOCK_TBL] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[aktivasi_aplikasi]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[aktivasi_aplikasi](
	[ID] [bigint] IDENTITY(1,1) NOT NULL,
	[ALAMAT_PENGUSAHA] [varchar](max) NULL,
	[CERTIFICATE] [varchar](2000) NULL,
	[ID_MODUL] [varchar](255) NULL,
	[KODE_GUDANG] [varchar](10) NULL,
	[KPPBC] [varchar](255) NULL,
	[NAMA_PENGUSAHA] [varchar](255) NULL,
	[NOMOR_SKEP] [varchar](255) NULL,
	[NPWP] [varchar](255) NULL,
	[PASSWORD] [varchar](255) NULL,
	[PASSWORD_CERTIFICATE] [varchar](500) NULL,
	[PORT] [varchar](255) NULL,
	[TANGGAL_SKEP] [datetime2](0) NULL,
	[URL] [varchar](255) NULL,
	[USERNAME] [varchar](255) NULL,
PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[C3LC_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[C3LC_TBL](
	[C3LC_ITMCD] [varchar](50) NOT NULL,
	[C3LC_NLOTNO] [varchar](50) NOT NULL,
	[C3LC_NQTY] [int] NOT NULL,
	[C3LC_LOTNO] [varchar](50) NOT NULL,
	[C3LC_QTY] [int] NOT NULL,
	[C3LC_REFF] [varchar](50) NOT NULL,
	[C3LC_LINE] [int] NOT NULL,
	[C3LC_USRID] [varchar](40) NULL,
	[C3LC_LUPTD] [datetime] NULL,
	[C3LC_NEWID] [varchar](25) NULL,
	[C3LC_COMID] [varchar](25) NULL,
	[C3LC_DOC] [varchar](50) NULL,
	[C3LC_VALUE] [varchar](25) NULL,
PRIMARY KEY CLUSTERED 
(
	[C3LC_ITMCD] ASC,
	[C3LC_LOTNO] ASC,
	[C3LC_QTY] ASC,
	[C3LC_REFF] ASC,
	[C3LC_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[CHGPW_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[CHGPW_TBL](
	[CHGPW_USR] [varchar](25) NOT NULL,
	[CHGPW_CREATEDAT] [datetime] NULL,
	[CHGPW_CREATEDBY] [varchar](25) NULL,
	[CHGPW_VALUE] [varchar](100) NULL,
	[CHGPW_VALUEHT] [varchar](50) NULL,
	[CHGPW_PASSPERIOD] [char](1) NULL,
	[CHGPW_LINE] [int] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[composite_items]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[composite_items](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[item_code_main] [nvarchar](50) NOT NULL,
	[item_code_sub] [nvarchar](50) NOT NULL,
	[deleted_at] [datetime] NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[CSMLOG_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[CSMLOG_TBL](
	[CSMLOG_DOCNO] [varchar](50) NOT NULL,
	[CSMLOG_SUPZAJU] [varchar](50) NULL,
	[CSMLOG_SUPZNOPEN] [varchar](50) NULL,
	[CSMLOG_DESC] [varchar](100) NOT NULL,
	[CSMLOG_LINE] [int] NOT NULL,
	[CSMLOG_TYPE] [char](3) NULL,
	[CSMLOG_CREATED_AT] [datetime] NOT NULL,
	[CSMLOG_CREATED_BY] [varchar](20) NULL,
	[CSMLOG_IP] [varbinary](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[DLV_PKG_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[DLV_PKG_TBL](
	[DLV_PKG_DOC] [varchar](50) NOT NULL,
	[DLV_PKG_LINE] [int] NOT NULL,
	[DLV_PKG_P] [decimal](12, 3) NULL,
	[DLV_PKG_L] [decimal](12, 3) NULL,
	[DLV_PKG_T] [decimal](12, 3) NULL,
	[DLV_PKG_ITM] [varchar](50) NULL,
	[DLV_PKG_ITMTYPE] [varchar](50) NULL,
	[DLV_PKG_QTY] [decimal](12, 3) NULL,
	[DLV_PKG_NWG] [decimal](15, 6) NULL,
	[DLV_PKG_GWG] [decimal](15, 6) NULL,
	[DLV_PKG_MEASURE] [varchar](50) NULL,
 CONSTRAINT [PK__DLV_PKG___87637F3EE6AC3D16] PRIMARY KEY CLUSTERED 
(
	[DLV_PKG_DOC] ASC,
	[DLV_PKG_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[DLVCK_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[DLVCK_TBL](
	[DLVCK_TXID] [varchar](50) NOT NULL,
	[DLVCK_CUSTDO] [varchar](50) NULL,
	[DLVCK_ITMCD] [varchar](50) NULL,
	[DLVCK_CNFQTY] [int] NULL,
	[DLVCK_QTY] [int] NULL,
	[DLVCK_LINE] [int] NOT NULL,
 CONSTRAINT [PK__DLVCK_TB__DAB8F17A8D40C52A] PRIMARY KEY CLUSTERED 
(
	[DLVCK_TXID] ASC,
	[DLVCK_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[DLVRMSO_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[DLVRMSO_TBL](
	[DLVRMSO_TXID] [varchar](50) NOT NULL,
	[DLVRMSO_ITMID] [varchar](50) NULL,
	[DLVRMSO_ITMQT] [int] NULL,
	[DLVRMSO_CPO] [varchar](50) NULL,
	[DLVRMSO_CPOLINE] [varchar](50) NULL,
	[DLVRMSO_PRPRC] [decimal](15, 6) NULL,
	[DLVRMSO_LINE] [int] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[DLVRMSO_TXID] ASC,
	[DLVRMSO_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[DLVSCR_BB_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[DLVSCR_BB_TBL](
	[DLVSCR_BB_TXID] [varchar](50) NOT NULL,
	[DLVSCR_BB_ITMD1] [varchar](100) NULL,
	[DLVSCR_BB_ITMID] [varchar](50) NULL,
	[DLVSCR_BB_ITMQT] [decimal](12, 3) NULL,
	[DLVSCR_BB_HSCD] [varchar](20) NULL,
	[DLVSCR_BB_KODE_KANTOR] [varchar](20) NULL,
	[DLVSCR_BB_BCTYPE] [varchar](10) NULL,
	[DLVSCR_BB_AJU] [varchar](50) NULL,
	[DLVSCR_BB_NOPEN] [varchar](50) NULL,
	[DLVSCR_BB_TGLPEN] [date] NULL,
	[DLVSCR_BB_PRPRC] [decimal](15, 6) NULL,
	[DLVSCR_BB_ZPRPRC] [decimal](15, 6) NULL,
	[DLVSCR_BB_ITMTYPE] [varchar](50) NULL,
	[DLVSCR_BB_ITMNW] [decimal](15, 6) NULL,
	[DLVSCR_BB_ITMUOM] [varchar](50) NULL,
	[DLVSCR_BB_BC_DEDUCTION_TYPE] [char](1) NULL,
	[DLVSCR_BB_BCURUT] [int] NULL,
	[DLVSCR_BB_REMARK] [varchar](50) NULL,
	[DLVSCR_BB_MATA_UANG] [varchar](5) NULL,
	[DLVSCR_BB_BM] [decimal](18, 1) NULL,
	[DLVSCR_BB_PPN] [decimal](18, 1) NULL,
	[DLVSCR_BB_PPH] [decimal](18, 1) NULL,
	[DLVSCR_BB_LINE] [int] NOT NULL
) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [CI_DLVSCR_BB]    Script Date: 2025-04-28 7:54:02 AM ******/
CREATE UNIQUE CLUSTERED INDEX [CI_DLVSCR_BB] ON [dbo].[DLVSCR_BB_TBL]
(
	[DLVSCR_BB_TXID] ASC,
	[DLVSCR_BB_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[DLVSTS_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[DLVSTS_TBL](
	[DLVSTS_ID] [varchar](50) NOT NULL,
	[DLVSTS_CREATEDAT] [datetime] NULL,
	[DLVSTS_STS] [char](1) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[DLVSTS_ID] ASC,
	[DLVSTS_STS] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[downtime_category]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[downtime_category](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[description] [nvarchar](25) NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[failed_jobs]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[failed_jobs](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[connection] [nvarchar](max) NOT NULL,
	[queue] [nvarchar](max) NOT NULL,
	[payload] [nvarchar](max) NOT NULL,
	[exception] [nvarchar](max) NOT NULL,
	[failed_at] [datetime] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[FIFORM_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[FIFORM_TBL](
	[FIFORM_DT] [date] NULL,
	[FIFORM_BCTYPE] [varchar](50) NULL,
	[FIFORM_NOAJU] [varchar](50) NULL,
	[FIFORM_NOPEN] [varchar](50) NULL,
	[FIFORM_ITMCD] [varchar](50) NOT NULL,
	[FIFORM_QTY] [decimal](12, 3) NULL,
	[FIFORM_NOAJU_OUT] [varchar](50) NULL,
	[FIFORM_NOPEN_OUT] [varchar](50) NULL,
	[FIFORM_TM] [time](7) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[INT_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[INT_TBL](
	[INT_ITMCD] [varchar](50) NULL,
	[INT_LOTNO] [varchar](50) NULL,
	[INT_QTY] [int] NULL,
	[INT_TM] [datetime] NOT NULL,
	[INT_USR] [varchar](10) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[INT_TM] ASC,
	[INT_USR] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[inventory_pappers]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[inventory_pappers](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[nomor_urut] [int] NOT NULL,
	[item_code] [varchar](50) NOT NULL,
	[item_qty] [float] NOT NULL,
	[item_box] [int] NOT NULL,
	[checker_id] [varchar](9) NOT NULL,
	[auditor_id] [varchar](9) NULL,
	[created_by] [varchar](9) NOT NULL,
	[updated_by] [varchar](9) NULL,
	[deleted_at] [datetime] NULL,
	[deleted_by] [varchar](9) NULL,
	[item_location] [varchar](50) NULL,
	[item_location_group] [varchar](50) NULL,
	[confirm_at] [datetime] NULL,
 CONSTRAINT [PK__inventor__3213E83F3679C467] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[INVENTORY_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[INVENTORY_TBL](
	[ASSYNO] [varchar](50) NULL,
	[CLOTNO] [varchar](50) NULL,
	[CQTY] [numeric](5, 0) NULL,
	[CMODEL] [varchar](230) NULL,
	[CJOBNO] [varchar](50) NULL,
	[CRMK] [varchar](50) NULL,
	[REFNO] [varchar](50) NULL,
	[CLOC] [varchar](40) NULL,
	[CDATE] [datetime] NULL,
	[CPIC] [varchar](25) NULL,
	[MSTLOC_GRP] [varchar](25) NULL,
	[CPERIOD] [varchar](10) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[INVO_TBL]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[INVO_TBL](
	[INVO_BSGRP] [varchar](15) NOT NULL,
	[INVO_SUPCD] [varchar](50) NOT NULL,
	[INVO_SUPNO] [varchar](100) NOT NULL,
	[INVO_NO] [varchar](50) NOT NULL,
	[INVO_RCVDT] [date] NULL,
	[INVO_TYPE] [char](1) NOT NULL,
	[INVO_CRTDT] [datetime] NULL,
	[INVO_USRID] [varchar](30) NULL,
PRIMARY KEY CLUSTERED 
(
	[INVO_BSGRP] ASC,
	[INVO_SUPCD] ASC,
	[INVO_SUPNO] ASC,
	[INVO_NO] ASC,
	[INVO_TYPE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[ITH_BIN]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[ITH_BIN](
	[ITH_ITMCD] [varchar](50) NOT NULL,
	[ITH_DATE] [date] NULL,
	[ITH_FORM] [varchar](50) NULL,
	[ITH_DOC] [varchar](200) NULL,
	[ITH_QTY] [decimal](12, 3) NULL,
	[ITH_WH] [varchar](50) NULL,
	[ITH_LOC] [varchar](50) NULL,
	[ITH_SER] [varchar](21) NULL,
	[ITH_REMARK] [varchar](50) NULL,
	[ITH_LINE] [varchar](50) NULL,
	[ITH_EXPORTED] [char](1) NULL,
	[ITH_LUPDT] [datetime] NULL,
	[ITH_USRID] [varchar](9) NULL,
	[ITH_REASON] [varchar](50) NULL,
	[ITH_USRBIN] [varchar](50) NULL,
	[ITH_LUPDTBIN] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[jobs]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[jobs](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[queue] [nvarchar](255) NOT NULL,
	[payload] [nvarchar](max) NOT NULL,
	[attempts] [tinyint] NOT NULL,
	[reserved_at] [int] NULL,
	[available_at] [int] NOT NULL,
	[created_at] [int] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_access_rules]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_access_rules](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[user_id] [nvarchar](9) NOT NULL,
	[sheet_access] [nvarchar](3) NOT NULL,
	[deleted_at] [datetime] NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
	[line_code] [nvarchar](15) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_calc_resumes]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_calc_resumes](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[production_date] [date] NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[total_plan_worktime_morning] [float] NOT NULL,
	[total_plan_worktime_night] [float] NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_calc_templates]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_calc_templates](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[calculation_at] [datetime] NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[name] [nvarchar](25) NOT NULL,
	[worktype1] [float] NOT NULL,
	[worktype2] [float] NOT NULL,
	[worktype3] [float] NOT NULL,
	[worktype4] [float] NOT NULL,
	[worktype5] [float] NOT NULL,
	[worktype6] [float] NOT NULL,
	[status] [nvarchar](1) NOT NULL,
	[category] [nvarchar](2) NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_calcs]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_calcs](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[shift_code] [nvarchar](2) NOT NULL,
	[production_date] [date] NOT NULL,
	[calculation_at] [datetime] NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[worktype1] [float] NOT NULL,
	[worktype2] [float] NOT NULL,
	[worktype3] [float] NOT NULL,
	[worktype4] [float] NOT NULL,
	[worktype5] [float] NOT NULL,
	[worktype6] [float] NOT NULL,
	[flag_mot] [nvarchar](5) NULL,
	[efficiency] [float] NOT NULL,
	[plan_worktime] [float] NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
 CONSTRAINT [PK__keikaku___3213E83F3ABECEA1] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_comment_prodplans]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_comment_prodplans](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[production_date] [date] NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[cell_code] [nvarchar](15) NOT NULL,
	[comment] [nvarchar](55) NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
 CONSTRAINT [PK__keikaku___3213E83F2FAA5660] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_data]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_data](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[production_date] [date] NOT NULL,
	[seq] [smallint] NOT NULL,
	[model_code] [nvarchar](100) NOT NULL,
	[wo_code] [nvarchar](5) NOT NULL,
	[wo_full_code] [nvarchar](50) NOT NULL,
	[item_code] [nvarchar](50) NOT NULL,
	[lot_size] [int] NOT NULL,
	[plan_qty] [int] NOT NULL,
	[actual_qty] [int] NULL,
	[type] [nvarchar](200) NOT NULL,
	[specs] [nvarchar](50) NOT NULL,
	[specs_side] [nvarchar](50) NOT NULL,
	[cycle_time] [float] NOT NULL,
	[packaging] [nvarchar](200) NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
	[plan_morning_qty] [int] NULL,
	[plan_night_qty] [int] NULL,
	[bom_rev] [decimal](4, 2) NULL,
 CONSTRAINT [PK__keikaku___3213E83FC2697188] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_draft_data]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_draft_data](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[production_date] [date] NOT NULL,
	[seq] [smallint] NOT NULL,
	[model_code] [nvarchar](20) NOT NULL,
	[wo_code] [nvarchar](5) NOT NULL,
	[wo_full_code] [nvarchar](50) NULL,
	[item_code] [nvarchar](50) NOT NULL,
	[lot_size] [int] NOT NULL,
	[plan_qty] [int] NULL,
	[type] [nvarchar](100) NOT NULL,
	[specs] [nvarchar](50) NOT NULL,
	[specs_side] [nvarchar](50) NULL,
	[cycle_time] [float] NULL,
	[packaging] [nvarchar](5) NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[revision] [nvarchar](5) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
	[start_production_date] [date] NOT NULL,
	[shift] [nvarchar](1) NOT NULL,
	[file_year] [nvarchar](4) NOT NULL,
	[file_month] [nvarchar](2) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_input2s]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_input2s](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[production_date] [date] NOT NULL,
	[running_at] [datetime] NOT NULL,
	[wo_code] [nvarchar](50) NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[process_code] [nvarchar](15) NOT NULL,
	[process_seq] [smallint] NULL,
	[ok_qty] [float] NOT NULL,
	[seq_data] [int] NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_input3s]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_input3s](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[production_date] [date] NOT NULL,
	[running_at] [datetime] NOT NULL,
	[wo_code] [nvarchar](50) NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[process_code] [nvarchar](15) NOT NULL,
	[process_seq] [smallint] NULL,
	[ok_qty] [float] NOT NULL,
	[seq_data] [int] NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_model_changes]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_model_changes](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[production_date] [date] NOT NULL,
	[running_at] [datetime] NOT NULL,
	[wo_code] [nvarchar](50) NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[process_code] [nvarchar](15) NOT NULL,
	[process_seq] [smallint] NULL,
	[seq_data] [smallint] NULL,
	[change_flag] [nchar](1) NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_output2s]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_output2s](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[production_date] [date] NOT NULL,
	[running_at] [datetime] NOT NULL,
	[wo_code] [nvarchar](50) NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[process_code] [nvarchar](15) NOT NULL,
	[process_seq] [smallint] NULL,
	[ok_qty] [float] NOT NULL,
	[seq_data] [int] NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_outputs]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_outputs](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[production_date] [date] NOT NULL,
	[running_at] [datetime] NOT NULL,
	[wo_code] [nvarchar](50) NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[process_code] [nvarchar](15) NOT NULL,
	[process_seq] [smallint] NULL,
	[ok_qty] [float] NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
	[seq_data] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_releases]    Script Date: 2025-04-28 7:54:02 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_releases](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[release_flag] [nchar](1) NOT NULL,
	[production_date] [date] NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[keikaku_styles]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[keikaku_styles](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[production_date] [date] NOT NULL,
	[sheet_code] [nchar](1) NOT NULL,
	[styles] [nvarchar](max) NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[LOGSER_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[LOGSER_TBL](
	[LOGSER_KEYS] [varchar](200) NULL,
	[LOGSER_ITEM] [varchar](200) NULL,
	[LOGSER_QTY] [varchar](200) NULL,
	[LOGSER_LOT] [varchar](200) NULL,
	[LOGSER_JOB] [varchar](200) NULL,
	[LOGSER_DT] [datetime] NULL,
	[LOGSER_KEYS_RPLC] [varchar](200) NULL,
	[LOGSER_USRID] [varchar](200) NULL,
	[LOGSER_REMARK] [varchar](200) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[LOGXDATA_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[LOGXDATA_TBL](
	[LOGXDATA_USER] [varchar](50) NULL,
	[LOGXDATA_MENU] [varchar](50) NULL,
	[LOGXDATA_DT] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MACHINENTOOLS]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MACHINENTOOLS](
	[TYPE_BC] [varchar](50) NOT NULL,
	[NO_AJU] [varchar](50) NOT NULL,
	[TGL_AJU] [date] NOT NULL,
	[NOPEN] [varchar](50) NOT NULL,
	[TGL] [date] NOT NULL,
	[pkno] [varchar](max) NOT NULL,
	[TGL_KONTRAK] [date] NOT NULL,
	[JATUH_TEMPO] [varchar](50) NULL,
	[URAIAN_JENIS_BARANG] [varchar](max) NOT NULL,
	[PART_CODE] [varchar](50) NULL,
	[ASSET_NO] [varchar](max) NULL,
	[JML] [int] NOT NULL,
	[SAT] [varchar](50) NOT NULL,
	[PENGIRIM] [varchar](max) NOT NULL,
	[PENERIMA] [varchar](max) NOT NULL,
	[HS_CODE] [varchar](50) NOT NULL,
	[NO_SURAT_JALAN] [varchar](50) NOT NULL,
	[TGL_SURAT_JALAN] [date] NOT NULL,
	[mega_cust] [varchar](50) NOT NULL,
	[NO_URUT] [varchar](50) NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[master_hscode]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[master_hscode](
	[ItemCode] [nvarchar](255) NULL,
	[ItemName] [nvarchar](255) NULL,
	[HSCode] [nvarchar](255) NULL,
	[NetWeight] [float] NULL,
	[GrossWeight] [float] NULL,
	[BM] [float] NULL,
	[PPN] [float] NULL,
	[PPH] [float] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[master_hscode_omi]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[master_hscode_omi](
	[ITEMNO] [varchar](50) NOT NULL,
	[DESCRIPTION] [varchar](100) NULL,
	[NOHS] [varchar](50) NULL,
	[BM] [varchar](50) NULL,
	[PPN] [varchar](50) NULL,
	[Pph] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MASTERHSCD_STX]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MASTERHSCD_STX](
	[ItemCode] [varchar](50) NOT NULL,
	[TariffCodeSTX] [varchar](50) NOT NULL,
	[BM] [decimal](18, 1) NOT NULL,
	[PPN] [decimal](18, 1) NOT NULL,
	[PPH] [decimal](18, 1) NOT NULL,
	[Description] [varchar](50) NOT NULL,
	[MakerPartNo] [varchar](50) NOT NULL,
 CONSTRAINT [PK_MASTERHSCD_STX] PRIMARY KEY CLUSTERED 
(
	[ItemCode] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MBSG_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MBSG_TBL](
	[MBSG_BSGRP] [char](10) NOT NULL,
	[MBSG_DESC] [char](50) NULL,
	[MBSG_PIC] [char](50) NULL,
	[MBSG_MGRNM] [char](50) NULL,
	[MBSG_MGRDSG] [char](50) NULL,
	[MBSG_CUSCD] [char](10) NULL,
	[MBSG_CURCD] [char](4) NULL,
	[MBSG_LUPDT] [datetime] NULL,
	[MBSG_BFSTK] [char](1) NULL,
	[MBSG_MRPDT] [datetime] NULL,
	[MBSG_MRPEX] [char](1) NULL,
	[MBSG_KITEX] [char](1) NULL,
	[MBSG_PORLS] [datetime] NULL,
	[MBSG_CUTOFF] [datetime] NULL,
	[MBSG_WCUTOF] [datetime] NULL,
	[MBSG_LTIME] [int] NULL,
	[MBSG_WLTME] [int] NULL,
	[MBSG_ODTOD] [char](1) NULL,
	[MBSG_LTFLG] [char](1) NULL,
	[MBSG_WLTFG] [char](1) NULL,
	[MBSG_WHDFG] [char](1) NULL,
	[MBSG_PHDFG] [char](1) NULL,
	[MBSG_STKAC] [char](10) NULL,
	[MBSG_COGAC] [char](10) NULL,
	[MBSG_SALAC] [char](10) NULL,
	[MBSG_ATCAC] [char](10) NULL,
	[MBSG_USRID] [char](10) NULL,
	[MBSG_BFTYP] [char](1) NULL,
	[MBSG_EGLAC] [char](10) NULL,
	[MBSG_WORLS] [datetime] NULL,
	[MBSG_STKTY] [char](1) NULL,
	[MBSG_PRDCAT] [char](10) NULL,
	[MBSG_TEMCDE] [char](10) NULL,
	[MBSG_DPTCDE] [char](10) NULL,
	[MBSG_ACTFG] [char](1) NULL,
	[MBSG_IAYR] [int] NULL,
	[MBSG_IAMTH] [int] NULL,
	[MBSG_CLFG] [char](1) NULL,
	[MBSG_CRLMT] [numeric](13, 2) NULL,
	[MBSG_STXG] [char](10) NULL,
	[MBSG_BAFG] [char](1) NULL,
	[MBSG_CUSINFO] [char](50) NULL,
	[MBSG_CCVDL] [char](1) NULL,
	[MBSG_IDNO] [char](25) NULL,
	[MBSG_BLINE] [char](10) NULL,
	[MBSG_BRNBL] [char](10) NULL,
	[MBSG_EUSER] [char](10) NULL,
	[MBSG_EPGRP] [char](10) NULL,
	[MBSG_EPPNM] [char](50) NULL,
	[MBSG_FGSAC] [char](10) NULL,
	[MBSG_OICAC] [char](10) NULL,
	[MBSG_SUSAC] [char](10) NULL,
	[MBSG_ASYAC] [char](10) NULL,
	[MBSG_UTDAC] [char](10) NULL,
	[MBSG_WOMATCH] [char](1) NULL,
	[MBSG_ARMATCH] [char](1) NULL,
	[MBSG_INTFG] [char](1) NULL,
	[MBSG_FOREXFG] [char](1) NULL,
	[MBSG_GITAC] [char](10) NULL,
	[MBSG_SALRAC] [char](10) NULL,
	[MBSG_BGTYPE] [char](1) NULL,
	[MBSG_PRGID] [char](20) NULL,
	[MBSG_KITLT] [int] NULL,
	[MBSG_WIPFGAC] [char](10) NULL,
	[MBSG_LEDSTARV] [numeric](4, 2) NULL,
 CONSTRAINT [PK_MBSG_TBL] PRIMARY KEY CLUSTERED 
(
	[MBSG_BSGRP] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MCNSGN_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MCNSGN_TBL](
	[MCNSGN_CUSCD] [varchar](10) NOT NULL,
	[MCNSGN_NM] [varchar](45) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[MCNSGN_CUSCD] ASC,
	[MCNSGN_NM] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MCONA_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MCONA_TBL](
	[MCONA_DOC] [varchar](50) NOT NULL,
	[MCONA_DATE] [date] NULL,
	[MCONA_DUEDATE] [date] NULL,
	[MCONA_ITMCD] [varchar](50) NOT NULL,
	[MCONA_QTY] [decimal](12, 3) NULL,
	[MCONA_BALQTY] [decimal](12, 3) NULL,
	[MCONA_ITMTYPE] [char](1) NULL,
	[MCONA_LINE] [int] NOT NULL,
	[MCONA_REMARK] [varchar](50) NULL,
	[MCONA_PERPRC] [decimal](15, 6) NULL,
	[MCONA_BSGRP] [varchar](25) NULL,
	[MCONA_CUSCD] [varchar](25) NULL,
	[MCONA_KNDJOB] [varchar](50) NULL,
	[MCONA_LCNSNUM] [varchar](50) NULL,
	[MCONA_LCNSDT] [date] NULL,
	[MCONA_LUPDT] [datetime] NULL,
	[MCONA_USRID] [varchar](50) NULL,
 CONSTRAINT [PK__MCONA_TB__DFF6933BCC049F6F] PRIMARY KEY CLUSTERED 
(
	[MCONA_DOC] ASC,
	[MCONA_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MDEPT_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MDEPT_TBL](
	[MDEPT_CD] [varchar](8) NOT NULL,
	[MDEPT_NM] [varchar](50) NULL,
 CONSTRAINT [PK_MDEPT_TBL] PRIMARY KEY CLUSTERED 
(
	[MDEPT_CD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MEXRATE_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MEXRATE_TBL](
	[MEXRATE_CURR] [varchar](3) NOT NULL,
	[MEXRATE_TYPE] [varchar](5) NOT NULL,
	[MEXRATE_DT] [date] NOT NULL,
	[MEXRATE_VAL] [decimal](15, 6) NULL,
	[MEXRATE_LUPDT] [datetime] NULL,
	[MEXRATE_USRID] [varchar](50) NOT NULL,
 CONSTRAINT [PK__MEXRATE___1C4BE16210F4A89E] PRIMARY KEY CLUSTERED 
(
	[MEXRATE_CURR] ASC,
	[MEXRATE_TYPE] ASC,
	[MEXRATE_DT] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MIGITMQTY_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MIGITMQTY_TBL](
	[MIGITMQTY_CD] [varchar](50) NOT NULL,
	[MIGITMQTY_QTY] [int] NULL,
 CONSTRAINT [PK_MIGITMQTY_TBL] PRIMARY KEY CLUSTERED 
(
	[MIGITMQTY_CD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[migrations]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[migrations](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[migration] [nvarchar](255) NOT NULL,
	[batch] [int] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MIGSUB]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MIGSUB](
	[RMCD] [varchar](50) NOT NULL,
	[RMQTY] [int] NOT NULL,
	[REFNO] [varchar](50) NOT NULL,
	[RMALT] [varchar](50) NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MITM_BAKUP]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MITM_BAKUP](
	[ITMCD] [varchar](255) NULL,
	[MITM_HSCD] [varchar](255) NULL,
	[MITM_BM] [varchar](255) NULL,
	[MITM_PPN] [varchar](255) NULL,
	[MITM_PPH] [varchar](255) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MITMGRP_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MITMGRP_TBL](
	[MITMGRP_ITMCD] [varchar](50) NOT NULL,
	[MITMGRP_ITMCD_GRD] [varchar](50) NOT NULL,
	[MITMGRP_LUPDT] [datetime] NULL,
	[MITMGRP_USRID] [varchar](50) NULL,
PRIMARY KEY CLUSTERED 
(
	[MITMGRP_ITMCD] ASC,
	[MITMGRP_ITMCD_GRD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MITMHSCD_HIS_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MITMHSCD_HIS_TBL](
	[MITMHSCD_HIS_ITMCD] [varchar](50) NOT NULL,
	[MITMHSCD_HIS_HSCD] [varchar](20) NULL,
	[MITMHSCD_HIS_BM] [decimal](18, 1) NULL,
	[MITMHSCD_HIS_PPN] [decimal](18, 1) NULL,
	[MITMHSCD_HIS_PPH] [decimal](18, 1) NULL,
	[MITMHSCD_HIS_UPDATED_AT] [datetime] NOT NULL,
	[MITMHSCD_HIS_UPDATED_BY] [varchar](15) NULL,
	[MITMHSCD_HIS_LINE] [int] NOT NULL,
 CONSTRAINT [PK__MITMHSCD__9A458558131E6DD1] PRIMARY KEY CLUSTERED 
(
	[MITMHSCD_HIS_ITMCD] ASC,
	[MITMHSCD_HIS_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MITMPROCE_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MITMPROCE_TBL](
	[MITMPROCE_ITMCD] [varchar](50) NOT NULL,
	[MITMPROCE_SEQ] [int] NOT NULL,
	[MITMPROCE_SEQNM] [varchar](50) NOT NULL,
	[MITMPROCE_CAT] [varchar](5) NOT NULL,
	[MITMPROCE_PCBPSHEET] [int] NOT NULL,
 CONSTRAINT [PK__MITMPROC__27230C743C157BF0] PRIMARY KEY CLUSTERED 
(
	[MITMPROCE_ITMCD] ASC,
	[MITMPROCE_SEQ] ASC,
	[MITMPROCE_SEQNM] ASC,
	[MITMPROCE_CAT] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MMADE_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MMADE_TBL](
	[MMADE_CD] [varchar](2) NOT NULL,
	[MMADE_NM] [varchar](50) NULL,
	[MMADE_LUPDT] [datetime] NULL,
	[MMADE_USRID] [varchar](50) NULL,
 CONSTRAINT [PK_MMADE_TBL] PRIMARY KEY CLUSTERED 
(
	[MMADE_CD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MMDL_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MMDL_TBL](
	[MMDL_CD] [varchar](2) NOT NULL,
	[MMDL_NM] [varchar](50) NULL,
 CONSTRAINT [PK_MMDL_TBL] PRIMARY KEY CLUSTERED 
(
	[MMDL_CD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[monitors]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[monitors](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[job_id] [nvarchar](255) NOT NULL,
	[name] [nvarchar](255) NULL,
	[queue] [nvarchar](255) NULL,
	[started_at] [datetime] NULL,
	[started_at_exact] [nvarchar](255) NULL,
	[finished_at] [datetime] NULL,
	[finished_at_exact] [nvarchar](255) NULL,
	[time_elapsed] [float] NULL,
	[failed] [bit] NOT NULL,
	[attempt] [int] NOT NULL,
	[progress] [int] NULL,
	[exception] [nvarchar](max) NULL,
	[exception_message] [nvarchar](max) NULL,
	[exception_class] [nvarchar](max) NULL,
	[data] [nvarchar](max) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MPROCD_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MPROCD_TBL](
	[MPROCD_MDLCD] [varchar](50) NOT NULL,
	[MPROCD_MDLRW] [smallint] NULL,
	[MPROCD_MDLTYP] [varchar](40) NULL,
	[MPROCD_SEQNO] [smallint] NOT NULL,
	[MPROCD_PRCD] [varchar](50) NULL,
	[MPROCD_SLINE] [varchar](10) NOT NULL,
	[MPROCD_CT] [decimal](15, 6) NOT NULL,
	[MPROCD_LINE] [smallint] NOT NULL,
	[MPROCD_UPDATED_BY] [varchar](10) NULL,
	[MPROCD_UPDATED_AT] [datetime] NULL,
	[MPROCD_CD] [varchar](30) NULL,
	[MPROCD_ISACTIVE] [char](1) NULL,
 CONSTRAINT [PK__MPROCD_T__00E6C41A2E89F41D] PRIMARY KEY CLUSTERED 
(
	[MPROCD_MDLCD] ASC,
	[MPROCD_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[ms_item]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[ms_item](
	[item_code] [varchar](100) NOT NULL,
	[item_name] [varchar](200) NULL,
	[item_length] [numeric](18, 4) NULL,
	[item_width] [numeric](18, 4) NULL,
	[item_height] [numeric](18, 4) NULL,
	[item_weight] [numeric](18, 4) NULL,
	[part_number] [varchar](100) NULL,
	[part_name] [varchar](200) NULL,
	[notes] [varchar](999) NULL,
	[unit_code] [varchar](100) NULL,
	[qtyperpack] [numeric](18, 4) NULL,
	[pack_style] [varchar](100) NULL,
	[pack_weight] [numeric](18, 4) NULL,
	[item_type_code] [varchar](100) NULL,
	[item_group_code] [varchar](100) NULL,
	[safety_stock] [numeric](18, 4) NULL,
	[vendor_code] [varchar](100) NULL,
	[cust_code] [varchar](100) NULL,
	[type] [varchar](3) NULL,
	[gr_unit] [numeric](18, 4) NULL,
	[m2_unit] [numeric](18, 4) NULL,
	[model] [varchar](100) NULL,
	[hs_code] [varchar](100) NULL,
	[division_code] [varchar](100) NULL,
	[rak] [varchar](100) NULL,
	[sn] [int] NULL,
	[code] [varchar](10) NULL,
	[no_rak] [varchar](10) NULL,
	[cat_rak] [varchar](100) NULL,
	[created_by] [varchar](50) NULL,
	[created_date] [datetime2](7) NULL,
	[updated_by] [varchar](50) NULL,
	[updated_date] [datetime2](7) NULL,
	[inactive] [int] NULL,
	[inventory_acc_number] [varchar](100) NULL,
	[bea_masuk] [numeric](18, 2) NULL,
	[ppn] [numeric](18, 2) NULL,
	[pph] [numeric](18, 2) NULL,
	[P1] [numeric](10, 0) NULL,
	[P2] [numeric](10, 0) NULL,
	[P3] [numeric](10, 0) NULL,
	[P4] [numeric](10, 0) NULL,
	[P5] [numeric](10, 0) NULL,
	[P6] [numeric](10, 0) NULL,
	[P7] [numeric](10, 0) NULL,
	[P8] [numeric](10, 0) NULL,
	[P9] [numeric](10, 0) NULL,
	[P10] [numeric](10, 0) NULL,
	[bm_percent] [int] NULL,
	[hs_code1] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MSG_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MSG_TBL](
	[MSG_FR] [varchar](9) NOT NULL,
	[MSG_TO] [varchar](9) NOT NULL,
	[MSG_TXT] [varchar](250) NULL,
	[MSG_REFFDATA] [text] NULL,
	[MSG_DOC] [varchar](50) NULL,
	[MSG_LINE] [int] NOT NULL,
	[MSG_TOPIC] [varchar](50) NOT NULL,
	[MSG_CREATEDAT] [datetime] NOT NULL,
	[MSG_UPDATEDAT] [datetime] NULL,
	[MSG_READAT] [datetime] NULL,
 CONSTRAINT [PK__MSG_TBL__B3A7CB222BF0ED97] PRIMARY KEY CLUSTERED 
(
	[MSG_FR] ASC,
	[MSG_TO] ASC,
	[MSG_LINE] ASC,
	[MSG_TOPIC] ASC,
	[MSG_CREATEDAT] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MSLSPRICE_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MSLSPRICE_TBL](
	[MSLSPRICE_ITMCD] [varchar](50) NOT NULL,
	[MSLSPRICE_CUSCD] [varchar](50) NOT NULL,
	[MSLSPRICE_PRICE] [decimal](15, 6) NULL,
	[MSLSPRICE_LUPDT] [datetime] NULL,
	[MSLSPRICE_USRID] [varchar](50) NULL,
PRIMARY KEY CLUSTERED 
(
	[MSLSPRICE_ITMCD] ASC,
	[MSLSPRICE_CUSCD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MSTGRP_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MSTGRP_TBL](
	[MSTGRP_ID] [varchar](20) NOT NULL,
	[MSTGRP_NM] [varchar](50) NULL,
 CONSTRAINT [PK_MSTGRP_TBL] PRIMARY KEY CLUSTERED 
(
	[MSTGRP_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MSTTRANS_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MSTTRANS_TBL](
	[MSTTRANS_ID] [varchar](15) NOT NULL,
	[MSTTRANS_TYPE] [varchar](40) NULL,
	[MSTTRANS_LUPDT] [datetime] NULL,
	[MSTTRANS_USRID] [varchar](20) NULL,
	[deleted_at] [datetime] NULL,
	[deleted_by] [varchar](9) NULL,
 CONSTRAINT [PK_MSTTRANS_TBL] PRIMARY KEY CLUSTERED 
(
	[MSTTRANS_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[MUM_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[MUM_TBL](
	[MUM_CD] [varchar](25) NOT NULL,
	[MUM_NM] [varchar](50) NULL,
 CONSTRAINT [PK_MUM_TBL] PRIMARY KEY CLUSTERED 
(
	[MUM_CD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[NSPSCN_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[NSPSCN_TBL](
	[NSPSCN_DOC] [varchar](50) NOT NULL,
	[NSPSCN_ITMCD] [varchar](50) NOT NULL,
	[NSPSCN_QTY] [decimal](12, 3) NOT NULL,
	[NSPSCN_LOT] [varchar](50) NULL,
	[NSPSCN_LINE] [int] NOT NULL,
	[NSPSCN_LUPTD] [datetime] NULL,
	[NSPSCN_USRID] [varchar](20) NULL,
 CONSTRAINT [PK__NSPSCN_T__30E55324DA932A3C] PRIMARY KEY CLUSTERED 
(
	[NSPSCN_DOC] ASC,
	[NSPSCN_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[oauth_access_tokens]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[oauth_access_tokens](
	[id] [nvarchar](100) NOT NULL,
	[user_id] [bigint] NULL,
	[client_id] [int] NOT NULL,
	[name] [nvarchar](255) NULL,
	[scopes] [nvarchar](max) NULL,
	[revoked] [bit] NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[expires_at] [datetime] NULL,
 CONSTRAINT [oauth_access_tokens_id_primary] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[oauth_auth_codes]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[oauth_auth_codes](
	[id] [nvarchar](100) NOT NULL,
	[user_id] [bigint] NOT NULL,
	[client_id] [int] NOT NULL,
	[scopes] [nvarchar](max) NULL,
	[revoked] [bit] NOT NULL,
	[expires_at] [datetime] NULL,
 CONSTRAINT [oauth_auth_codes_id_primary] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[oauth_clients]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[oauth_clients](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[user_id] [bigint] NULL,
	[name] [nvarchar](255) NOT NULL,
	[secret] [nvarchar](100) NULL,
	[redirect] [nvarchar](max) NOT NULL,
	[personal_access_client] [bit] NOT NULL,
	[password_client] [bit] NOT NULL,
	[revoked] [bit] NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[oauth_personal_access_clients]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[oauth_personal_access_clients](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[client_id] [int] NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[oauth_refresh_tokens]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[oauth_refresh_tokens](
	[id] [nvarchar](100) NOT NULL,
	[access_token_id] [nvarchar](100) NOT NULL,
	[revoked] [bit] NOT NULL,
	[expires_at] [datetime] NULL,
 CONSTRAINT [oauth_refresh_tokens_id_primary] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[OLD_FG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[OLD_FG](
	[SER_ITMID] [varchar](50) NULL,
	[PARTCODE] [varchar](50) NULL,
	[PER] [float] NULL,
	[SUBSTITUTED] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[OLD_FG2]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[OLD_FG2](
	[SER_ITMID] [varchar](50) NULL,
	[PARTCODE] [varchar](50) NULL,
	[PER] [int] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[part_treatments]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[part_treatments](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[code] [nvarchar](50) NOT NULL,
	[treatment] [nchar](1) NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[password_reset_tokens]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[password_reset_tokens](
	[email] [nvarchar](255) NOT NULL,
	[token] [nvarchar](255) NOT NULL,
	[created_at] [datetime] NULL,
 CONSTRAINT [password_reset_tokens_email_primary] PRIMARY KEY CLUSTERED 
(
	[email] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[personal_access_tokens]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[personal_access_tokens](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[tokenable_type] [nvarchar](255) NOT NULL,
	[tokenable_id] [bigint] NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[token] [nvarchar](64) NOT NULL,
	[abilities] [nvarchar](max) NULL,
	[last_used_at] [datetime] NULL,
	[expires_at] [datetime] NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PICKWAY_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PICKWAY_TBL](
	[PICKWAY_ITMCD] [varchar](50) NOT NULL,
	[PICKWAY_CAT] [char](1) NOT NULL,
	[PICKWAY_LUPDT] [datetime] NULL,
	[PICKWAY_USRID] [varchar](15) NULL,
PRIMARY KEY CLUSTERED 
(
	[PICKWAY_ITMCD] ASC,
	[PICKWAY_CAT] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PND_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PND_TBL](
	[PND_DOC] [varchar](50) NOT NULL,
	[PND_DT] [date] NULL,
	[PND_ITMCD] [varchar](50) NOT NULL,
	[PND_ITMLOT] [varchar](50) NULL,
	[PND_QTY] [decimal](12, 3) NULL,
	[PND_REMARK] [varchar](50) NULL,
	[PND_LUPDT] [datetime] NULL,
	[PND_USRID] [varchar](9) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PNDSCN_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PNDSCN_TBL](
	[PNDSCN_ID] [varchar](20) NOT NULL,
	[PNDSCN_DOC] [varchar](50) NOT NULL,
	[PNDSCN_ITMCD] [varchar](50) NOT NULL,
	[PNDSCN_LOTNO] [varchar](50) NULL,
	[PNDSCN_QTY] [decimal](12, 3) NULL,
	[PNDSCN_SER] [varchar](50) NULL,
	[PNDSCN_SAVED] [char](1) NULL,
	[PNDSCN_LUPDT] [datetime] NULL,
	[PNDSCN_USRID] [varchar](50) NULL,
 CONSTRAINT [PK_PNDSCN_TBL] PRIMARY KEY CLUSTERED 
(
	[PNDSCN_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PNDSER_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PNDSER_TBL](
	[PNDSER_DOC] [varchar](50) NOT NULL,
	[PNDSER_SER] [varchar](21) NOT NULL,
	[PNDSER_DT] [date] NULL,
	[PNDSER_QTY] [decimal](12, 3) NULL,
	[PNDSER_SCANNED] [char](1) NULL,
	[PNDSER_SAVED] [char](1) NULL,
	[PNDSER_REMARK] [varchar](50) NULL,
	[PNDSER_LUPDT] [datetime] NULL,
	[PNDSER_USRID] [varchar](50) NULL,
 CONSTRAINT [PK__PNDFG_TB__0F81EDCB7F74AF03] PRIMARY KEY CLUSTERED 
(
	[PNDSER_DOC] ASC,
	[PNDSER_SER] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PO0_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PO0_TBL](
	[PO0_NO] [varchar](12) NOT NULL,
	[PO0_ISCUSTOMS] [char](1) NOT NULL,
	[PO0_ISSUDT] [date] NULL,
	[PO0_LUPDT] [datetime] NULL,
	[PO0_LUPDT_BY] [varchar](25) NULL,
 CONSTRAINT [PK_PO0_TBL] PRIMARY KEY CLUSTERED 
(
	[PO0_NO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PODISC_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PODISC_TBL](
	[PODISC_PONO] [varchar](12) NOT NULL,
	[PODISC_REV] [char](1) NULL,
	[PODISC_LINE] [int] NOT NULL,
	[PODISC_DESC] [varchar](50) NULL,
	[PODISC_DISC] [decimal](12, 3) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[POSBJT_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[POSBJT_TBL](
	[POSBJT_CD] [varchar](2) NOT NULL,
	[POSBJT_NM] [varchar](50) NULL,
 CONSTRAINT [PK_POSBJT_TBL] PRIMARY KEY CLUSTERED 
(
	[POSBJT_CD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[POSTING_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[POSTING_TBL](
	[POSTING_DOC] [varchar](50) NOT NULL,
	[POSTING_STATUS] [char](1) NOT NULL,
	[POSTING_STARTED_AT] [datetime] NOT NULL,
	[POSTING_FINISHED_AT] [datetime] NULL,
	[POSTING_BY] [varchar](15) NOT NULL,
	[POSTING_IP] [varchar](30) NOT NULL,
	[POSTING_DATALINE] [bigint] IDENTITY(1,1) NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PROCDET_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PROCDET_TBL](
	[id] [int] IDENTITY(0,1) NOT NULL,
	[PROCMSTR_ID] [int] NULL,
	[PROCDET_SEQ] [int] NULL,
	[PROCDET_MDLCD] [varchar](100) NULL,
	[PROCDET_CD] [varchar](100) NULL,
	[PROCDET_LINE] [varchar](100) NULL,
	[PROCDET_CT] [decimal](38, 3) NULL,
	[PROCDET_AKM] [varchar](100) NULL,
	[PROCDET_PSSS] [int] NULL,
	[PROCDET_ITER_ID] [varchar](100) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
 CONSTRAINT [PROCDET_TBL_PK] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[process_masters]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[process_masters](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[assy_code] [nvarchar](45) NOT NULL,
	[model_code] [nvarchar](100) NOT NULL,
	[model_type] [nvarchar](15) NOT NULL,
	[process_code] [nvarchar](15) NOT NULL,
	[process_seq] [smallint] NULL,
	[cycle_time] [decimal](14, 2) NOT NULL,
	[deleted_at] [datetime] NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
	[valid_date_time] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PROCMSTR_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PROCMSTR_TBL](
	[id] [int] IDENTITY(0,1) NOT NULL,
	[PROCMSTR_PROCD] [varchar](100) NULL,
	[PROCMSTR_REMARK] [varchar](100) NULL,
	[PROCMSTR_ISLINE] [int] NULL,
	[PROCMSTR_ISCT] [int] NULL,
	[PROCMSTR_MFGCD] [varchar](100) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[PROCMSTR_ISPROCESS] [int] NULL,
	[PROCMSTR_HEADSEQ] [int] NULL,
	[PROCMSTR_SCR_FLAG] [numeric](38, 0) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[production_downtime]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[production_downtime](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[shift_code] [nvarchar](2) NOT NULL,
	[production_date] [date] NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[downtime_code] [int] NOT NULL,
	[req_minutes] [float] NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
	[running_at] [datetime] NULL,
	[remark] [nvarchar](500) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[production_output]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[production_output](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[wo_code] [nvarchar](50) NOT NULL,
	[item_code] [nvarchar](50) NOT NULL,
	[shift_code] [nvarchar](2) NOT NULL,
	[production_date] [date] NOT NULL,
	[running_at] [datetime] NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[process_code] [nvarchar](15) NOT NULL,
	[process_seq] [smallint] NOT NULL,
	[ok_qty] [int] NULL,
	[ng_qty] [int] NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
	[cycle_time] [float] NOT NULL,
	[model_code] [nvarchar](10) NULL,
	[model_type] [nvarchar](5) NULL,
	[input_qty] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[production_times]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[production_times](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[shift_code] [nvarchar](2) NOT NULL,
	[production_date] [date] NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[working_hours] [float] NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PSI_TRACE]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PSI_TRACE](
	[PSI_PSNNO] [varchar](100) NULL,
	[PSI_WONO] [varchar](100) NULL,
	[PSI_LINENNO] [varchar](50) NULL,
	[PSI_PRCD] [varchar](50) NULL,
	[PSI_JOBNO] [varchar](50) NULL,
	[PSI_MC] [varchar](50) NULL,
	[PSI_ORDERNO] [varchar](50) NULL,
	[PSI_CONFIRM] [varchar](100) NULL,
	[PSI_LUPBY] [varchar](50) NULL,
	[PSI_LUPDT] [datetime] NULL,
	[PSI_STS] [varchar](50) NULL,
	[PSI_ITMCD] [varchar](50) NULL,
	[PSI_LOTNO] [varchar](50) NULL,
	[PSI_QTY] [numeric](18, 0) NULL,
	[PSI_UNQ] [varchar](50) NULL,
	[PSI_RMK] [varchar](50) NULL,
	[PSI_ID] [varchar](50) NULL,
	[PSI_MDLCD] [varchar](50) NULL,
	[PSI_BOMRV] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PST_LOG_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PST_LOG_TBL](
	[PST_PARTCD] [varchar](50) NULL,
	[PST_ASSYCD] [varchar](50) NULL,
	[PST_REQQTY] [decimal](12, 3) NULL,
	[PST_EXCQTY] [decimal](12, 3) NULL,
	[PST_TXID] [varchar](50) NULL,
	[PST_TGLPEN] [date] NULL,
	[PST_NOPEN] [varchar](10) NULL,
	[PST_NOAJU] [varchar](30) NULL,
	[PST_REMARK] [varchar](50) NULL,
	[PST_CREATED_AT] [datetime] NULL,
	[PST_USR] [varchar](40) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PTTRN_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PTTRN_TBL](
	[PTTRN_NO] [varchar](50) NOT NULL,
	[PTTRN_DATE] [date] NULL,
	[PTTRN_ID] [varchar](50) NULL,
	[PTTRN_USRID] [varchar](50) NULL,
	[PTTRN_LUPDT] [datetime] NULL,
 CONSTRAINT [PK_PTTRN_TBL] PRIMARY KEY CLUSTERED 
(
	[PTTRN_NO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[PWPOL_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[PWPOL_TBL](
	[PWPOL_HISTORY] [smallint] NULL,
	[PWPOL_MAXAGE] [int] NULL,
	[PWPOL_MINAGE] [smallint] NULL,
	[PWPOL_LENGTH] [smallint] NULL,
	[PWPOL_ISCOMPLEX] [char](1) NULL,
	[PWPOL_XSPECIALCHAR] [char](1) NULL,
	[PWPOL_XNUMERIC] [char](1) NULL,
	[PWPOL_XALPHA] [nchar](1) NULL,
	[PWPOL_LINE] [char](1) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[raw_material_label_prints]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[raw_material_label_prints](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[code] [nvarchar](21) NOT NULL,
	[item_code] [nvarchar](50) NOT NULL,
	[doc_code] [nvarchar](50) NOT NULL,
	[parent_code] [nvarchar](21) NULL,
	[quantity] [float] NOT NULL,
	[lot_code] [nvarchar](50) NOT NULL,
	[action] [nvarchar](15) NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[pc_name] [nvarchar](50) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[raw_material_labels]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[raw_material_labels](
	[code] [nvarchar](21) NOT NULL,
	[item_code] [nvarchar](50) NOT NULL,
	[doc_code] [nvarchar](50) NOT NULL,
	[parent_code] [nvarchar](21) NULL,
	[quantity] [float] NOT NULL,
	[lot_code] [nvarchar](50) NOT NULL,
	[splitted] [nvarchar](1) NULL,
	[combined] [nvarchar](1) NULL,
	[composed] [nvarchar](1) NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
	[deleted_at] [datetime] NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[item_value] [nvarchar](25) NULL,
 CONSTRAINT [raw_material_labels_code_primary] PRIMARY KEY CLUSTERED 
(
	[code] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RCVD_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RCVD_TBL](
	[RCVD_ID] [bigint] IDENTITY(1,1) NOT NULL,
	[RCVD_ITMID] [varchar](50) NULL,
	[RCVD_SERNNUM] [varchar](45) NULL,
	[RCVD_ASSETNUM] [varchar](40) NULL,
	[RCVD_PO] [varchar](12) NULL,
	[RCVD_DO] [varchar](100) NULL,
	[RCVD_CREATED_DT] [datetime] NULL,
	[RCVD_CREATED_BY] [varchar](9) NULL,
	[RCVD_LUPDATE_DT] [datetime] NULL,
	[RCVD_LUPDATE_BY] [varchar](9) NULL,
 CONSTRAINT [PK_RCVD_TBL] PRIMARY KEY CLUSTERED 
(
	[RCVD_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RCVDOCS_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RCVDOCS_TBL](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[RCVDOCS_SUPCD] [varchar](15) NOT NULL,
	[RCVDOCS_DONO] [varchar](50) NULL,
	[RCVDOCS_INVNO] [varchar](50) NOT NULL,
	[RCVDOCS_TAXINVNO] [varchar](45) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[created_by] [varchar](15) NULL,
	[updated_by] [varchar](15) NULL,
	[deleted_by] [varchar](15) NULL,
 CONSTRAINT [PK_RCVDOCS_TBL] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RCVFGSCN_BAK2023]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RCVFGSCN_BAK2023](
	[RCVFGSCN_ID] [varchar](20) NOT NULL,
	[RCVFGSCN_DOC] [varchar](50) NULL,
	[RCVFGSCN_LOC] [varchar](50) NOT NULL,
	[RCVFGSCN_SER] [varchar](50) NOT NULL,
	[RCVFGSCN_ITMCD] [varchar](50) NOT NULL,
	[RCVFGSCN_SERQTY] [decimal](12, 3) NULL,
	[RCVFGSCN_SAVED] [varchar](2) NULL,
	[RCVFGSCN_LUPDT] [datetime] NULL,
	[RCVFGSCN_USRID] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RCVFGSCN_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RCVFGSCN_TBL](
	[RCVFGSCN_ID] [varchar](20) NOT NULL,
	[RCVFGSCN_DOC] [varchar](50) NULL,
	[RCVFGSCN_LOC] [varchar](50) NOT NULL,
	[RCVFGSCN_SER] [varchar](50) NOT NULL,
	[RCVFGSCN_ITMCD] [varchar](50) NOT NULL,
	[RCVFGSCN_SERQTY] [decimal](12, 3) NULL,
	[RCVFGSCN_SAVED] [varchar](2) NULL,
	[RCVFGSCN_LUPDT] [datetime] NULL,
	[RCVFGSCN_USRID] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RCVNIUPLOAD]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RCVNIUPLOAD](
	[PO_NO] [nvarchar](255) NULL,
	[PO_ITMNM] [nvarchar](255) NULL,
	[PO_QTY] [nvarchar](255) NULL,
	[Recvd] [nvarchar](255) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RCVPKG_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RCVPKG_TBL](
	[RCVPKG_AJU] [varchar](50) NOT NULL,
	[RCVPKG_LINE] [int] NOT NULL,
	[RCVPKG_DOC] [varchar](50) NOT NULL,
	[RCVPKG_JUMLAH_KEMASAN] [int] NOT NULL,
	[RCVPKG_KODE_JENIS_KEMASAN] [varchar](5) NULL,
	[RCVPKG_CREATED_AT] [datetime] NULL,
	[RCVPKG_CREATED_BY] [varchar](10) NULL,
	[RCVPKG_UPDATED_AT] [datetime] NULL,
	[RCVPKG_UPDATED_BY] [varchar](10) NULL,
PRIMARY KEY CLUSTERED 
(
	[RCVPKG_AJU] ASC,
	[RCVPKG_LINE] ASC,
	[RCVPKG_DOC] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[REELC_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[REELC_TBL](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[REELC_DOC] [varchar](50) NULL,
	[REELC_ITMCD] [varchar](50) NULL,
	[REELC_FOR_PROCESS] [varchar](20) NULL,
	[REELC_FOR_MC] [varchar](20) NULL,
	[REELC_FOR_MCZ] [varchar](20) NULL,
	[REELC_UNIQUE1] [varchar](20) NULL,
	[REELC_QTY1] [int] NULL,
	[REELC_LOT1] [varchar](50) NULL,
	[REELC_UNIQUE2] [varchar](20) NULL,
	[REELC_QTY2] [int] NULL,
	[REELC_LOT2] [varchar](50) NULL,
	[REELC_UNIQUE3] [varchar](20) NULL,
	[REELC_QTY3] [int] NULL,
	[REELC_LOT3] [varchar](50) NULL,
	[REELC_UNIQUE4] [varchar](20) NULL,
	[REELC_QTY4] [int] NULL,
	[REELC_LOT4] [varchar](50) NULL,
	[REELC_UNIQUE5] [varchar](20) NULL,
	[REELC_QTY5] [int] NULL,
	[REELC_LOT5] [varchar](50) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[created_by] [varchar](15) NULL,
 CONSTRAINT [PK_REELC_TBL] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Requirement$]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Requirement$](
	[ITEM_CODE] [nvarchar](255) NULL,
	[REQQT] [float] NULL,
	[CAT] [nvarchar](255) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RESIM_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RESIM_TBL](
	[RESIM_WONO] [varchar](50) NOT NULL,
	[RESIM_MDLCD] [varchar](50) NOT NULL,
	[RESIM_BOMRV] [numeric](4, 2) NOT NULL,
	[RESIM_CREATED_AT] [datetime] NULL,
	[RESIM_CREATED_BY] [varchar](15) NULL,
	[RESIM_REMARK] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RETFG_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RETFG_TBL](
	[RETFG_DOCNO] [varchar](50) NOT NULL,
	[RETFG_ITMCD] [varchar](50) NOT NULL,
	[RETFG_QTY] [int] NOT NULL,
	[RETFG_RMRK] [varchar](10) NOT NULL,
	[RETFG_CUSCD] [varchar](25) NOT NULL,
	[RETFG_PLANT] [varchar](20) NOT NULL,
	[RETFG_LINE] [int] NOT NULL,
	[RETFG_STRDOC] [varchar](50) NOT NULL,
	[RETFG_STRDT] [date] NULL,
	[RETFG_CONSIGN] [varchar](25) NULL,
	[RETFG_NTCNO] [varchar](50) NULL,
	[RETFG_CAT] [char](1) NULL,
	[RETFG_SUPNO] [varchar](45) NULL,
	[RETFG_SUPCD] [varchar](15) NULL,
	[RETFG_USRID] [varchar](50) NULL,
	[RETFG_LUPDT] [datetime] NULL,
 CONSTRAINT [PK__RETFG_TB__0C867C4F5EF3D9D6] PRIMARY KEY CLUSTERED 
(
	[RETFG_DOCNO] ASC,
	[RETFG_LINE] ASC,
	[RETFG_STRDOC] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RETRM_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RETRM_TBL](
	[RETRM_DOC] [varchar](25) NOT NULL,
	[RETRM_LINE] [int] NOT NULL,
	[RETRM_ITMCD] [varchar](50) NOT NULL,
	[RETRM_OLDQTY] [decimal](12, 3) NOT NULL,
	[RETRM_NEWQTY] [decimal](12, 3) NOT NULL,
	[RETRM_LOTNUM] [varchar](50) NULL,
	[RETRM_CREATEDAT] [datetime] NOT NULL,
	[RETRM_LUPTDAT] [datetime] NULL,
	[RETRM_USRID] [varchar](15) NULL,
	[RETRM_UNIQUEKEY] [varchar](21) NULL,
PRIMARY KEY CLUSTERED 
(
	[RETRM_DOC] ASC,
	[RETRM_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RETSCN_LOG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RETSCN_LOG](
	[RETSCN_ID] [varchar](50) NOT NULL,
	[RETSCN_SPLDOC] [varchar](50) NULL,
	[RETSCN_CAT] [varchar](50) NULL,
	[RETSCN_LINE] [varchar](50) NULL,
	[RETSCN_FEDR] [varchar](50) NULL,
	[RETSCN_ORDERNO] [varchar](50) NULL,
	[RETSCN_ITMCD] [varchar](50) NULL,
	[RETSCN_LOT] [varchar](50) NULL,
	[RETSCN_QTYBEF] [decimal](12, 3) NULL,
	[RETSCN_QTYAFT] [decimal](12, 3) NULL,
	[RETSCN_CNTRYID] [varchar](2) NULL,
	[RETSCN_ROHS] [varchar](1) NOT NULL,
	[RETSCN_SAVED] [char](1) NULL,
	[RETSCN_HOLD] [char](1) NULL,
	[RETSCN_RMRK] [varchar](25) NULL,
	[RETSCN_LUPDT] [datetime] NULL,
	[RETSCN_USRID] [varchar](50) NULL,
	[RETSCN_PC] [varchar](25) NULL,
	[RETSCN_OPERATION] [varchar](25) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RLS_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RLS_TBL](
	[RLS_DOC] [varchar](50) NULL,
	[RLS_DT] [date] NULL,
	[RLS_ITMCD] [varchar](50) NULL,
	[RLS_ITMLOT] [varchar](50) NULL,
	[RLS_QTY] [int] NULL,
	[RLS_REMARK] [varchar](50) NULL,
	[RLS_REFFDOC] [varchar](50) NULL,
	[RLS_LINE] [int] NULL,
	[RLS_LUPDT] [datetime] NULL,
	[RLS_USRID] [varchar](9) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[RQSRMRK_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[RQSRMRK_TBL](
	[RQSRMRK_CD] [varchar](2) NOT NULL,
	[RQSRMRK_DESC] [varchar](50) NULL,
	[deleted_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
 CONSTRAINT [PK_RQSRMRK_TBL] PRIMARY KEY CLUSTERED 
(
	[RQSRMRK_CD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[scheduller_time_check]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[scheduller_time_check](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[count] [int] NOT NULL,
	[jobs_name] [nvarchar](255) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SCNDOCITM_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SCNDOCITM_TBL](
	[SCNDOCITM_DOCNO] [varchar](50) NOT NULL,
	[SCNDOCITM_ITMCD] [varchar](40) NOT NULL,
	[SCNDOCITM_LOTNO] [varchar](50) NOT NULL,
	[SCNDOCITM_QTY] [int] NOT NULL,
	[SCNDOCITM_LINE] [int] NOT NULL,
	[SCNDOCITM_CREATEDAT] [datetime] NOT NULL,
	[SCNDOCITM_CREATEDBY] [varchar](10) NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[SCNDOCITM_DOCNO] ASC,
	[SCNDOCITM_ITMCD] ASC,
	[SCNDOCITM_LOTNO] ASC,
	[SCNDOCITM_QTY] ASC,
	[SCNDOCITM_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SCRSCN_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SCRSCN_TBL](
	[SCRSCN_ID] [varchar](50) NOT NULL,
	[SCRSCN_DOC] [varchar](50) NULL,
	[SCRSCN_ITMCD] [varchar](50) NULL,
	[SCRSCN_LOTNO] [varchar](50) NULL,
	[SCRSCN_QTY] [decimal](12, 3) NULL,
	[SCRSCN_SER] [varchar](50) NULL,
	[SCRSCN_SAVED] [char](1) NULL,
	[SCRSCN_LUPDT] [datetime] NULL,
	[SCRSCN_USRID] [varchar](50) NULL,
 CONSTRAINT [PK_SCRSCN_TBL] PRIMARY KEY CLUSTERED 
(
	[SCRSCN_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SCRSER_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SCRSER_TBL](
	[SCRSER_DOC] [varchar](50) NOT NULL,
	[SCRSER_SER] [varchar](21) NOT NULL,
	[SCRSER_DT] [date] NULL,
	[SCRSER_QTY] [decimal](12, 3) NULL,
	[SCRSER_SCANNED] [char](1) NULL,
	[SCRSER_SAVED] [char](1) NULL,
	[SCRSER_REFFDOC] [varchar](50) NULL,
	[SCRSER_REMARK] [varchar](50) NULL,
	[SCRSER_LUPDT] [datetime] NULL,
	[SCRSER_USRID] [varchar](50) NULL,
 CONSTRAINT [PK__SCRFG_TB__0F81EDCB7F74AF03] PRIMARY KEY CLUSTERED 
(
	[SCRSER_DOC] ASC,
	[SCRSER_SER] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SER_WIP_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SER_WIP_TBL](
	[SER_ID] [varchar](21) NOT NULL,
	[SER_DOC] [varchar](50) NULL,
	[SER_ITMID] [varchar](50) NULL,
	[SER_QTY] [decimal](12, 3) NULL,
	[SER_QTYLOT] [bigint] NULL,
	[SER_LOTNO] [varchar](50) NULL,
	[SER_REFNO] [varchar](50) NULL,
	[SER_SHEET] [int] NULL,
	[SER_PRDDT] [date] NULL,
	[SER_PRDSHFT] [varchar](50) NULL,
	[SER_PRDLINE] [varchar](100) NULL,
	[SER_RMRK] [varchar](200) NULL,
	[SER_DOCTYPE] [char](1) NULL,
	[SER_ROHS] [char](1) NULL,
	[SER_CNTRYID] [varchar](2) NULL,
	[SER_FORCUST] [char](3) NULL,
	[SER_RAWTXT] [varchar](200) NULL,
	[SER_CAT] [char](1) NULL,
	[SER_BSGRP] [varchar](50) NULL,
	[SER_CUSCD] [varchar](50) NULL,
	[SER_GORNG] [char](1) NULL,
	[SER_RMUSE_COMFG] [char](10) NULL,
	[SER_RMUSE_COMFG_DT] [datetime] NULL,
	[SER_RMUSE_COMFG_USRID] [varchar](50) NULL,
	[SER_GRADE] [varchar](10) NULL,
	[SER_SIMBASE] [char](1) NULL,
	[SER_LUPDT] [datetime] NULL,
	[SER_USRID] [varchar](50) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[SER_STATUS] [varchar](45) NULL,
	[printed_at] [datetime] NULL,
	[printed_by] [varchar](9) NULL,
 CONSTRAINT [PK_SRWIPFG_TBL] PRIMARY KEY CLUSTERED 
(
	[SER_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SERC_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SERC_TBL](
	[SERC_NEWID] [varchar](21) NOT NULL,
	[SERC_COMID] [varchar](21) NOT NULL,
	[SERC_COMJOB] [varchar](50) NULL,
	[SERC_COMQTY] [bigint] NULL,
	[SERC_USRID] [varchar](50) NULL,
	[SERC_LUPDT] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[SERC_NEWID] ASC,
	[SERC_COMID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SERRC_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SERRC_TBL](
	[SERRC_SER] [varchar](21) NOT NULL,
	[SERRC_JM] [varchar](9) NULL,
	[SERRC_BOMPN] [varchar](50) NULL,
	[SERRC_BOMPNQTY] [int] NULL,
	[SERRC_LOTNO] [varchar](50) NULL,
	[SERRC_LINE] [int] NOT NULL,
	[SERRC_LOC] [varchar](50) NULL,
	[SERRC_NASSYCD] [varchar](50) NULL,
	[SERRC_DOCST] [varchar](50) NULL,
	[SERRC_DOCSTDT] [date] NULL,
	[SERRC_SERX] [varchar](21) NULL,
	[SERRC_SERXRAWTXT] [varchar](200) NULL,
	[SERRC_SERXQTY] [int] NULL,
	[SERRC_JMOPT] [varchar](10) NULL,
	[SERRC_USRID] [varchar](50) NULL,
	[SERRC_LUPDT] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SPCLACCP_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SPCLACCP_TBL](
	[SPCLACCP_MDLCD] [varchar](40) NOT NULL,
	[SPCLACCP_BOMPN] [varchar](40) NOT NULL,
	[SPCLACCP_SUBPN] [varchar](40) NOT NULL,
	[SPCLACCP_RMRK] [varbinary](50) NULL,
	[SPCLACCP_USRID] [varchar](15) NULL,
	[SPCLACCP_LUPTD] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[SPCLACCP_MDLCD] ASC,
	[SPCLACCP_BOMPN] ASC,
	[SPCLACCP_SUBPN] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SPL_LOG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SPL_LOG](
	[SPL_DOC] [varchar](50) NOT NULL,
	[SPL_DOCNO] [varchar](50) NULL,
	[SPL_CAT] [varchar](50) NOT NULL,
	[SPL_LINE] [varchar](50) NOT NULL,
	[SPL_FEDR] [varchar](50) NOT NULL,
	[SPL_PROCD] [varchar](20) NULL,
	[SPL_RMRK] [varchar](50) NULL,
	[SPL_BG] [varchar](50) NULL,
	[SPL_MC] [varchar](40) NULL,
	[SPL_ORDERNO] [varchar](50) NOT NULL,
	[SPL_RACKNO] [varchar](50) NULL,
	[SPL_ITMCD] [varchar](50) NOT NULL,
	[SPL_QTYUSE] [decimal](12, 3) NULL,
	[SPL_QTYREQ] [decimal](12, 3) NULL,
	[SPL_MS] [char](1) NULL,
	[SPL_USRGRP] [varchar](50) NULL,
	[SPL_FMDL] [varchar](50) NULL,
	[SPL_JOBNO_REC_RM] [varchar](1) NULL,
	[SPL_LUPDT] [datetime] NULL,
	[SPL_USRID] [varchar](50) NULL,
	[SPL_REFDOCCAT] [varchar](50) NULL,
	[SPL_REFDOCNO] [varchar](50) NULL,
	[SPL_LINEDATA] [int] NULL,
	[SPL_PRNKIDT] [datetime] NULL,
	[SPL_ITMRMRK] [varchar](150) NULL,
	[updated_at] [datetime] NULL,
	[created_at] [datetime] NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[SPLREFF_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[SPLREFF_TBL](
	[SPLREFF_DOC] [varchar](50) NOT NULL,
	[SPLREFF_REQ_PART] [varchar](50) NOT NULL,
	[SPLREFF_REQ_QTY] [decimal](12, 3) NULL,
	[SPLREFF_ACT_PART] [varchar](50) NULL,
	[SPLREFF_ACT_QTY] [decimal](12, 3) NULL,
	[SPLREFF_ACT_LOTNUM] [varchar](50) NULL,
	[SPLREFF_RMRK] [varchar](100) NULL,
	[SPLREFF_ITMCAT] [varchar](30) NULL,
	[SPLREFF_FEDR] [varchar](2) NULL,
	[SPLREFF_MCZ] [varchar](20) NULL,
	[SPLREFF_LINEPRD] [varchar](20) NULL,
	[SPLREFF_LINE] [int] NOT NULL,
	[SPLREFF_DATE] [date] NULL,
	[SPLREFF_SAVED] [varchar](1) NULL,
	[SPLREFF_CREATEDAT] [datetime] NULL,
	[SPLREFF_CREATEDBY] [varchar](9) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[STK_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[STK_TBL](
	[STK_PERIOD] [char](6) NOT NULL,
	[STK_DT1] [datetime] NULL,
	[STK_DT2] [datetime] NULL,
	[STK_TYPE] [varchar](50) NULL,
 CONSTRAINT [PK_STK_TBL] PRIMARY KEY CLUSTERED 
(
	[STK_PERIOD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[STX_HSCD]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[STX_HSCD](
	[Item_Code] [varchar](50) NOT NULL,
	[Tariff_Code_STX] [varchar](15) NOT NULL,
	[BM] [varchar](5) NOT NULL,
	[PPN] [varchar](15) NOT NULL,
	[PPH] [varchar](5) NOT NULL,
	[Description] [varchar](max) NOT NULL,
 CONSTRAINT [PK_STX_HSCD] PRIMARY KEY CLUSTERED 
(
	[Item_Code] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[sub_consigments]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[sub_consigments](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[code] [nvarchar](12) NOT NULL,
	[parent_code] [nvarchar](12) NOT NULL,
	[as_default] [nchar](1) NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Supplier]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Supplier](
	[MSUP_SUPCD] [varchar](max) NULL,
	[MSUP_SUPCR] [varchar](max) NULL,
	[MSUP_SUPNM] [varchar](max) NULL,
	[MSUP_ABBRV] [varchar](max) NULL,
	[MSUP_SUPTY] [varchar](max) NULL,
	[MSUP_CTRCD] [varchar](50) NULL,
	[MSUP_PTERM] [varchar](max) NULL,
	[MSUP_TAXCD] [varchar](50) NULL,
	[MSUP_ADDR1] [varchar](max) NULL,
	[MSUP_ADDR2] [varchar](max) NULL,
	[MSUP_ADDR3] [varchar](max) NULL,
	[MSUP_ADDR4] [varchar](max) NULL,
	[MSUP_ADDR5] [varchar](max) NULL,
	[MSUP_ADDR6] [varchar](max) NULL,
	[MSUP_ARECD] [varchar](50) NULL,
	[MSUP_TELNO] [varchar](max) NULL,
	[MSUP_TELNO2] [varchar](max) NULL,
	[MSUP_FAXNO] [varchar](max) NULL,
	[MSUP_FAXNO2] [varchar](max) NULL,
	[MSUP_TELEX] [nvarchar](50) NULL,
	[MSUP_EMAIL] [nvarchar](50) NULL,
	[MSUP_PIC] [nvarchar](50) NULL,
	[MSUP_PIC2] [nvarchar](50) NULL,
	[MSUP_LPODT] [nvarchar](50) NULL,
	[MSUP_REM] [nvarchar](100) NULL,
	[MSUP_REVISE] [tinyint] NULL,
	[MSUP_PAYTY] [nvarchar](50) NULL,
	[MSUP_PLTDAY] [tinyint] NULL,
	[MSUP_AIRLT] [tinyint] NULL,
	[MSUP_SPFG] [tinyint] NULL,
	[MSUP_LUPDT] [nvarchar](50) NULL,
	[MSUP_POTO] [nvarchar](50) NULL,
	[MSUP_POCUR] [nvarchar](50) NULL,
	[MSUP_SACTY] [nvarchar](50) NULL,
	[MSUP_USRID] [nvarchar](50) NULL,
	[MSUP_CRLMT] [int] NULL,
	[MSUP_TAXREG] [nvarchar](50) NULL,
	[MSUP_TRADE] [tinyint] NULL,
	[MSUP_SNMCD] [nvarchar](50) NULL,
	[MSUP_AMTDEC] [tinyint] NULL,
	[MSUP_AMTROUND] [nvarchar](50) NULL,
	[MSUP_PAYEECD] [tinyint] NULL,
	[MSUP_TOBANKMSG] [nvarchar](150) NULL,
	[MSUP_SUPNM1] [nvarchar](50) NULL,
	[MSUP_PRGID] [nvarchar](50) NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[sync_xtrf_hs]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[sync_xtrf_hs](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[xdocument_number] [nvarchar](35) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[created_by] [nvarchar](16) NOT NULL,
	[updated_by] [nvarchar](16) NULL,
	[deleted_at] [datetime] NULL,
	[deleted_by] [nvarchar](16) NULL,
	[synchronized_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TEMPDATA_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TEMPDATA_TBL](
	[TEMPDATA_IP] [varchar](45) NULL,
	[TEMPDATA_VAL] [text] NULL,
	[TEMPDATA_CTG] [varchar](50) NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TEMPFSO_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TEMPFSO_TBL](
	[TEMPFSO_NO] [varchar](30) NULL,
	[TEMPFSO_LINE] [varchar](4) NULL,
	[TEMPFSO_MDL] [varchar](50) NULL,
	[TEMPFSO_QTY] [bigint] NULL,
	[TEMPFSO_PLOTQTY] [bigint] NULL,
	[TEMPFSO_SILINE] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[tr_pch_rcv_det]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[tr_pch_rcv_det](
	[trans_no] [varchar](100) NOT NULL,
	[seq_num] [int] NOT NULL,
	[po_no] [varchar](100) NULL,
	[item_code] [varchar](100) NULL,
	[location_to] [varchar](100) NULL,
	[rcv_qty] [numeric](10, 4) NULL,
	[def_qty] [numeric](10, 4) NULL,
	[inv_gr] [numeric](18, 4) NULL,
	[inv_m2] [numeric](18, 4) NULL,
	[pack_qty] [numeric](18, 4) NULL,
	[net_price] [numeric](18, 5) NULL,
	[fob_cif_price] [numeric](18, 4) NULL,
	[bm_percent] [numeric](18, 4) NULL,
	[bm_fee] [numeric](18, 4) NULL,
	[expired_date] [date] NULL,
	[lot_code] [varchar](100) NULL,
	[over_qty] [int] NULL,
	[out_qty] [numeric](18, 4) NULL,
	[po_status] [varchar](5) NULL,
	[po_seq_num] [int] NULL,
	[status] [varchar](5) NULL,
	[invoice_qty] [numeric](18, 4) NULL,
	[new_code] [varchar](100) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[tr_pch_rcv_det3]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[tr_pch_rcv_det3](
	[trans_no] [varchar](100) NOT NULL,
	[seq_num] [int] NOT NULL,
	[item_code] [varchar](100) NULL,
	[po_no] [varchar](100) NULL,
	[location_to] [varchar](100) NULL,
	[rcv_qty] [numeric](18, 4) NULL,
	[lot_code] [varchar](100) NULL,
	[net_price] [numeric](18, 5) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[tr_pch_rcv_head]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[tr_pch_rcv_head](
	[trans_no] [varchar](100) NOT NULL,
	[trans_date] [date] NULL,
	[trans_stat] [int] NULL,
	[vendor_code] [varchar](100) NULL,
	[delivery_no] [varchar](100) NULL,
	[performance] [varchar](30) NULL,
	[expired_date] [date] NULL,
	[division_code] [varchar](100) NULL,
	[notes] [text] NULL,
	[import] [int] NULL,
	[bc_fee] [numeric](18, 4) NULL,
	[freight] [numeric](18, 4) NULL,
	[insurance] [numeric](18, 4) NULL,
	[curr_rate] [numeric](18, 4) NULL,
	[model] [varchar](100) NULL,
	[custom_category] [varchar](100) NULL,
	[custom_doc] [varchar](100) NULL,
	[custom_no] [varchar](100) NULL,
	[nw] [numeric](16, 2) NULL,
	[gw] [numeric](16, 2) NULL,
	[bc_amount] [numeric](16, 2) NULL,
	[created_by] [varchar](50) NULL,
	[created_date] [datetime2](7) NULL,
	[updated_by] [varchar](50) NULL,
	[updated_date] [datetime2](7) NULL,
	[approved_by] [varchar](50) NULL,
	[approved_date] [datetime2](7) NULL,
	[posted_by] [varchar](50) NULL,
	[posted_date] [datetime2](7) NULL,
	[canceled_by] [varchar](50) NULL,
	[canceled_date] [datetime2](7) NULL,
	[no_aju] [varchar](50) NULL,
	[curr_cd] [varchar](50) NULL,
	[nopen] [varchar](26) NULL,
	[curr_code] [varchar](50) NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[transfer_indirect_rm_details]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[transfer_indirect_rm_details](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[deleted_by] [nvarchar](9) NULL,
	[updated_by] [nvarchar](9) NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[id_header] [bigint] NOT NULL,
	[model] [nvarchar](95) NULL,
	[assy_code] [nvarchar](50) NULL,
	[part_code] [nvarchar](50) NOT NULL,
	[part_name] [nvarchar](50) NULL,
	[usage_qty] [float] NULL,
	[req_qty] [float] NOT NULL,
	[job] [nvarchar](50) NULL,
	[sup_qty] [float] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[transfer_indirect_rm_headers]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[transfer_indirect_rm_headers](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[doc_code] [nvarchar](45) NOT NULL,
	[doc_order] [bigint] NOT NULL,
	[issue_date] [date] NOT NULL,
	[location_from] [nvarchar](45) NOT NULL,
	[location_to] [nvarchar](45) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
	[submitted_by] [nvarchar](9) NULL,
	[submitted_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TRFD_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TRFD_TBL](
	[TRFD_DOC] [varchar](25) NOT NULL,
	[TRFD_ITEMCD] [varchar](50) NOT NULL,
	[TRFD_QTY] [decimal](12, 3) NULL,
	[TRFD_CREATED_BY] [varchar](9) NULL,
	[TRFD_CREATED_DT] [datetime] NULL,
	[TRFD_LAST_UPDATED_BY] [varchar](9) NULL,
	[TRFD_UPDATED_DT] [datetime] NULL,
	[TRFD_DELETED_BY] [varchar](9) NULL,
	[TRFD_DELETED_DT] [datetime] NULL,
	[TRFD_LINE] [bigint] IDENTITY(1,1) NOT NULL,
	[TRFD_RECEIVE_BY] [varchar](9) NULL,
	[TRFD_RECEIVE_DT] [datetime] NULL,
	[TRFD_REFFERENCE_DOCNO] [varchar](12) NULL,
 CONSTRAINT [PK_TRFD_TBL] PRIMARY KEY CLUSTERED 
(
	[TRFD_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TRFFGSCN_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TRFFGSCN_TBL](
	[TRFFGSCN_ID] [varchar](20) NULL,
	[TRFFGSCN_LOC] [varchar](50) NULL,
	[TRFFGSCN_SER] [varchar](50) NULL,
	[TRFFGSCN_LOT] [varchar](50) NULL,
	[TRFFGSCN_ITMCD] [varchar](50) NULL,
	[TRFFGSCN_FROMWH] [varchar](50) NULL,
	[TRFFGSCN_TOWH] [varchar](50) NULL,
	[TRFFGSCN_TORACK] [varchar](50) NULL,
	[TRFFGSCN_SERQTY] [decimal](12, 3) NOT NULL,
	[TRFFGSCN_SAVED] [varchar](2) NULL,
	[TRFFGSCN_LUPDT] [datetime] NULL,
	[TRFFGSCN_USRID] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TRFH_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TRFH_TBL](
	[TRFH_DOC] [varchar](25) NOT NULL,
	[TRFH_CREATED_DT] [datetime] NULL,
	[TRFH_CREATED_BY] [varchar](9) NULL,
	[TRFH_ISSUE_DT] [date] NOT NULL,
	[TRFH_LOC_FR] [varchar](50) NOT NULL,
	[TRFH_LOC_TO] [varchar](50) NOT NULL,
	[TRFH_ORDER] [int] NOT NULL,
	[TRFH_APPROVED_DT] [datetime] NULL,
	[TRFH_APPROVED_BY] [varchar](9) NULL,
 CONSTRAINT [PK_TRFH_TBL] PRIMARY KEY CLUSTERED 
(
	[TRFH_DOC] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TRFSET_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TRFSET_TBL](
	[TRFSET_LINE] [bigint] IDENTITY(1,1) NOT NULL,
	[TRFSET_WH] [varchar](50) NULL,
	[TRFSET_APPROVER] [varchar](9) NULL,
	[TRFSET_CREATED_BY] [varchar](9) NULL,
	[TRFSET_CREATED_DT] [datetime] NULL,
	[TRFSET_DELETED_BY] [varchar](9) NULL,
	[TRFSET_DELETED_DT] [datetime] NULL,
 CONSTRAINT [PK_TRFSET_TBL] PRIMARY KEY CLUSTERED 
(
	[TRFSET_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[TXROUTE_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[TXROUTE_TBL](
	[TXROUTE_ID] [varchar](50) NULL,
	[TXROUTE_WH] [varchar](50) NULL,
	[TXROUTE_WHINC] [varchar](50) NULL,
	[TXROUTE_WHOUT] [varchar](50) NULL,
	[TXROUTE_FORM_INC] [varchar](50) NULL,
	[TXROUTE_FORM_OUT] [varchar](50) NULL,
	[TXROUTE_RMRK] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[UPLOAD_PARTLIST]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[UPLOAD_PARTLIST](
	[Partcode] [varchar](50) NULL,
	[Manufacturercode] [varchar](50) NULL,
	[QTY] [int] NULL,
	[ASSYCODE] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[User_htx]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[User_htx](
	[UserId] [varchar](10) NOT NULL,
	[Passwd] [varchar](8) NULL,
	[Name] [varchar](20) NULL,
	[Authority] [varchar](12) NULL,
 CONSTRAINT [PK_Usertbl2] PRIMARY KEY CLUSTERED 
(
	[UserId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[users]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[users](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[email] [nvarchar](255) NOT NULL,
	[email_verified_at] [datetime] NULL,
	[password] [nvarchar](255) NOT NULL,
	[remember_token] [nvarchar](100) NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[value_checking_histories]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[value_checking_histories](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[code] [nvarchar](21) NULL,
	[item_code] [nvarchar](50) NOT NULL,
	[doc_code] [nvarchar](50) NOT NULL,
	[quantity] [float] NOT NULL,
	[lot_code] [nvarchar](50) NOT NULL,
	[item_value] [nvarchar](15) NOT NULL,
	[checking_status] [nvarchar](2) NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[client_ip] [nvarchar](50) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[w_i_p_outputs]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[w_i_p_outputs](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[deleted_at] [datetime] NULL,
	[production_date] [date] NOT NULL,
	[running_at] [datetime] NOT NULL,
	[shift_code] [nvarchar](1) NOT NULL,
	[wo_code] [nvarchar](5) NOT NULL,
	[wo_full_code] [nvarchar](50) NOT NULL,
	[item_code] [nvarchar](50) NOT NULL,
	[line_code] [nvarchar](15) NOT NULL,
	[model_code] [nvarchar](15) NOT NULL,
	[type] [nvarchar](50) NOT NULL,
	[specs] [nvarchar](50) NOT NULL,
	[ok_qty] [float] NOT NULL,
	[created_by] [nvarchar](9) NOT NULL,
	[updated_by] [nvarchar](9) NULL,
	[deleted_by] [nvarchar](9) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_ChkPcb]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_ChkPcb](
	[Box_id] [varchar](25) NULL,
	[AssyNo] [varchar](20) NULL,
	[cModel] [varchar](50) NULL,
	[JM_No] [varchar](30) NULL,
	[Sq_no] [numeric](18, 0) NULL,
	[cPic] [varchar](25) NULL,
	[cdate] [datetime] NULL,
	[cresult] [varchar](20) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_CLS_JOB]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_CLS_JOB](
	[CLS_SPID] [varchar](50) NOT NULL,
	[CLS_WONO] [varchar](50) NULL,
	[CLS_PSNNO] [varchar](50) NULL,
	[CLS_LINENO] [varchar](20) NULL,
	[CLS_PROCD] [varchar](20) NULL,
	[CLS_MDLCD] [varchar](50) NULL,
	[CLS_BOMRV] [varchar](20) NULL,
	[CLS_QTY] [numeric](18, 0) NULL,
	[CLS_LUPDT] [datetime] NULL,
	[CLS_LUPBY] [varchar](50) NULL,
	[CLS_JOBNO] [varchar](50) NULL,
 CONSTRAINT [PK_WMS_CLS_JOB] PRIMARY KEY CLUSTERED 
(
	[CLS_SPID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_CR_HIS]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_CR_HIS](
	[WONO] [varchar](100) NULL,
	[PROCD] [varchar](50) NULL,
	[LINENO] [varchar](50) NULL,
	[ITMCD] [varchar](50) NULL,
	[LOTNO] [varchar](50) NULL,
	[QTY] [varchar](50) NULL,
	[UNIQECODE] [varchar](50) NULL,
	[LUPBY] [varchar](50) NULL,
	[LUPDT] [datetime] NULL,
	[REMARK] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_DBLC_HIS]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_DBLC_HIS](
	[DBLC_WONO] [char](25) NOT NULL,
	[DBLC_PROCD] [char](10) NOT NULL,
	[DBLC_LINENO] [char](10) NOT NULL,
	[DBLC_MCMCZITM] [char](40) NOT NULL,
	[DBLC_LINE] [int] IDENTITY(1,1) NOT NULL,
	[DBLC_ITMCD] [char](25) NULL,
	[DBLC_LOTNO] [char](25) NULL,
	[DBLC_LUPDT] [datetime] NULL,
	[DBLC_LUPBY] [char](12) NULL,
	[DBLC_MC] [varchar](100) NULL,
	[DBLC_MCZ] [varchar](50) NULL,
	[DBLC_MDLCD] [varchar](100) NULL,
	[DBLC_BOMRV] [varchar](20) NULL,
	[DBLC_QTY] [varchar](20) NULL,
	[DBLC_SPID] [varchar](50) NULL,
	[DBLC_UNQ] [varchar](50) NULL,
	[DBLC_PSNNO] [varchar](100) NULL,
	[DBLC_JOBNO] [varchar](100) NULL,
	[DBLC_MAINITMCD] [varchar](100) NULL,
	[DBLC_SUBITMCD] [varchar](100) NULL,
	[DBLC_REMARK] [varchar](100) NULL,
	[DBLC_Judge] [varchar](100) NULL,
	[DBLC_Check] [varchar](50) NULL,
 CONSTRAINT [PK_WMS_DBLC_HIS] PRIMARY KEY CLUSTERED 
(
	[DBLC_WONO] ASC,
	[DBLC_PROCD] ASC,
	[DBLC_LINENO] ASC,
	[DBLC_MCMCZITM] ASC,
	[DBLC_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 99) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_DLVCHK]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_DLVCHK](
	[dlv_id] [varchar](30) NULL,
	[dlv_itmcd] [varchar](30) NULL,
	[dlv_refno] [varchar](30) NULL,
	[dlv_qty] [int] NULL,
	[dlv_PIC] [varchar](50) NULL,
	[dlv_date] [datetime] NULL,
	[dlv_PicSend] [varchar](50) NULL,
	[dlv_DateSend] [datetime] NULL,
	[dlv_stchk] [int] NULL,
	[dlv_stcfm] [int] NULL,
	[dlv_transno] [varchar](20) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_DLVCHK_LOGS]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_DLVCHK_LOGS](
	[id] [bigint] IDENTITY(1,1) NOT NULL,
	[created_at] [datetime] NULL,
	[updated_at] [datetime] NULL,
	[dlv_id] [nvarchar](30) NOT NULL,
	[dlv_itmcd] [nvarchar](30) NOT NULL,
	[dlv_refno] [nvarchar](30) NOT NULL,
	[dlv_qty] [int] NOT NULL,
	[dlv_PIC] [nvarchar](50) NULL,
	[dlv_date] [datetime] NULL,
	[dlv_PicSend] [nvarchar](50) NULL,
	[dlv_DateSend] [datetime] NULL,
	[dlv_stchk] [int] NULL,
	[dlv_stcfm] [int] NULL,
	[dlv_transno] [nvarchar](20) NULL,
	[created_by] [nvarchar](9) NOT NULL,
 CONSTRAINT [PK__WMS_DLVC__3213E83F38953BDF] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_FDR_LOG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_FDR_LOG](
	[FDR_SPID] [varchar](50) NULL,
	[FDR_Old1] [varchar](50) NULL,
	[FDR_Old2] [varchar](50) NULL,
	[FDR_Itmcd1] [varchar](50) NULL,
	[FDR_itmcd2] [varchar](50) NULL,
	[FDR_New1] [varchar](50) NULL,
	[FDR_New2] [varchar](50) NULL,
	[FDR_Nitmcd1] [varchar](50) NULL,
	[FDR_Nitmcd2] [varchar](50) NULL,
	[FDR_Lot1] [varchar](50) NULL,
	[FDR_Lot2] [varchar](50) NULL,
	[FDR_Nlot1] [varchar](50) NULL,
	[FDR_Nlot2] [varchar](50) NULL,
	[FDR_QTY1] [numeric](18, 0) NULL,
	[FDR_QTY2] [numeric](18, 0) NULL,
	[FDR_NQTY1] [numeric](18, 0) NULL,
	[FDR_NQTY2] [numeric](18, 0) NULL,
	[FDR_UC1] [varchar](50) NULL,
	[FDR_UC2] [varchar](50) NULL,
	[FDR_NUC1] [varchar](50) NULL,
	[FDR_NUC2] [varchar](50) NULL,
	[FDR_MDLCD] [varchar](50) NULL,
	[FDR_Bomrv] [varchar](50) NULL,
	[FDR_Date] [datetime] NULL,
	[FDR_PIC] [varchar](50) NULL,
	[FDR_Remark] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_ICProg_tbl]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_ICProg_tbl](
	[IC_Itmcd] [varchar](50) NULL,
	[IC_Itmnm] [varchar](200) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_Inv]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_Inv](
	[cAssyNo] [varchar](25) NULL,
	[cLotNo] [varchar](25) NULL,
	[cQty] [numeric](5, 0) NULL,
	[cModel] [varchar](50) NULL,
	[cJobNo] [varchar](10) NULL,
	[cRmk] [varchar](50) NULL,
	[Refno] [varchar](50) NOT NULL,
	[cLoc] [varchar](25) NULL,
	[cDate] [datetime] NULL,
	[cPic] [varchar](25) NULL,
	[mstloc_grp] [varchar](25) NULL,
	[updated_at] [datetime] NULL,
	[updated_by] [varchar](25) NULL,
 CONSTRAINT [PK_WMS_Inv] PRIMARY KEY CLUSTERED 
(
	[Refno] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[wms_INVENTORY_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[wms_INVENTORY_TBL](
	[ASSYNO] [varchar](50) NULL,
	[CLOTNO] [varchar](50) NULL,
	[CQTY] [numeric](5, 0) NULL,
	[CMODEL] [varchar](230) NULL,
	[CJOBNO] [varchar](50) NULL,
	[CRMK] [varchar](50) NULL,
	[REFNO] [varchar](50) NULL,
	[CLOC] [varchar](40) NULL,
	[CDATE] [datetime] NULL,
	[CPIC] [varchar](25) NULL,
	[MSTLOC_GRP] [varchar](25) NULL,
	[CPERIOD] [varchar](10) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_InvRM]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_InvRM](
	[cPartCode] [varchar](30) NULL,
	[cLotNo] [varchar](50) NULL,
	[cQty] [int] NULL,
	[cLoc] [varchar](100) NULL,
	[cPic] [varchar](25) NULL,
	[cDate] [datetime] NULL,
	[cUC] [varchar](100) NULL,
	[cWH] [varchar](50) NULL,
	[cCategory] [varchar](100) NULL,
	[cline] [varchar](50) NULL,
	[cfeeder] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_Mail]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_Mail](
	[Mail_Omi] [varchar](max) NULL,
	[mail_ICTChLog] [varchar](250) NULL,
	[Mail_ccchlog] [varchar](max) NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_MST_FDR]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_MST_FDR](
	[FDR_No] [varchar](20) NULL,
	[FDR_MC] [varchar](20) NULL,
	[FDR_MCZ] [varchar](20) NULL,
	[FDR_SPID] [varchar](50) NULL,
	[FDR_FLG] [numeric](18, 0) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_PPSN2_LOG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_PPSN2_LOG](
	[PPSN2_STATUS] [char](10) NULL,
	[PPSN2_BSGRP] [char](10) NULL,
	[PPSN2_DOCNO] [char](25) NULL,
	[PPSN2_PSNNO] [char](25) NULL,
	[PPSN2_DATANO] [char](11) NULL,
	[PPSN2_LINENO] [char](10) NULL,
	[PPSN2_PROCD] [char](10) NULL,
	[PPSN2_FR] [char](1) NULL,
	[PPSN2_ITMCAT] [char](10) NULL,
	[PPSN2_MC] [char](10) NULL,
	[PPSN2_MCZ] [char](10) NULL,
	[PPSN2_MSFLG] [char](1) NULL,
	[PPSN2_SUBPN] [char](50) NULL,
	[PPSN2_QTPER] [decimal](12, 3) NULL,
	[PPSN2_REQQT] [decimal](12, 3) NULL,
	[PPSN2_MCEXCESS] [decimal](12, 3) NULL,
	[PPSN2_MCEXSRSV] [decimal](12, 3) NULL,
	[PPSN2_PSNQT] [decimal](12, 3) NULL,
	[PPSN2_PACKSZ1] [decimal](12, 3) NULL,
	[PPSN2_PICKQT1] [decimal](12, 3) NULL,
	[PPSN2_PACKSZ2] [decimal](12, 3) NULL,
	[PPSN2_PICKQT2] [decimal](12, 3) NULL,
	[PPSN2_PACKSZ3] [decimal](12, 3) NULL,
	[PPSN2_PICKQT3] [decimal](12, 3) NULL,
	[PPSN2_PACKSZ4] [decimal](12, 3) NULL,
	[PPSN2_PICKQT4] [decimal](12, 3) NULL,
	[PPSN2_PACKSZ5] [decimal](12, 3) NULL,
	[PPSN2_PICKQT5] [decimal](12, 3) NULL,
	[PPSN2_RTNFG] [char](1) NULL,
	[PPSN2_RTNQT] [decimal](12, 3) NULL,
	[PPSN2_SUFFIX] [char](2) NULL,
	[PPSN2_ACTQT] [decimal](12, 3) NULL,
	[PPSN2_PACKSZ6] [decimal](12, 3) NULL,
	[PPSN2_PICKQT6] [decimal](12, 3) NULL,
	[PPSN2_PACKSZ7] [decimal](12, 3) NULL,
	[PPSN2_PICKQT7] [decimal](12, 3) NULL,
	[PPSN2_PACKSZ8] [decimal](12, 3) NULL,
	[PPSN2_PICKQT8] [decimal](12, 3) NULL,
	[PPSN2_LUPDT] [datetime] NOT NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_PREP_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_PREP_TBL](
	[PREP_PSNNO] [varchar](100) NULL,
	[PREP_WONO] [varchar](100) NULL,
	[PREP_PROCD] [varchar](20) NULL,
	[PREP_LINENO] [varchar](20) NULL,
	[PREP_JOBNO] [varchar](50) NULL,
	[PREP_STSFG] [varchar](50) NULL,
	[PREP_LUPDT] [datetime] NULL,
	[PREP_LUPBY] [varchar](50) NULL,
	[PREP_MDLCD] [varchar](50) NULL,
	[PREP_BOMRV] [varchar](20) NULL,
	[PREP_SPID] [varchar](50) NULL,
	[PREP_Status] [varchar](50) NULL,
	[PREP_lupdt_com] [datetime] NULL,
	[PREP_LUPBY_COM] [varchar](100) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_RCICPR]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_RCICPR](
	[IC_TransNo] [varchar](50) NOT NULL,
	[IC_lupdt] [datetime] NULL,
	[IC_InsName] [varchar](100) NULL,
	[IC_DblChk] [varchar](100) NULL,
	[IC_MdlType] [varchar](250) NULL,
	[IC_JobNo] [varchar](20) NULL,
	[IC_LotSize] [varchar](10) NULL,
	[IC_Itmcd] [varchar](50) NULL,
	[IC_Itmnm] [varchar](250) NULL,
	[IC_MCNo] [varchar](10) NULL,
	[IC_LblDot] [varchar](20) NULL,
	[IC_Checksum] [varchar](50) NULL,
	[IC_Qty] [numeric](18, 0) NULL,
	[IC_Rmk] [varchar](250) NULL,
	[ic_Loc] [varchar](20) NULL,
	[ic_insdate] [datetime] NULL,
	[ic_updqty] [varchar](50) NULL,
	[IC_Qtybfr] [numeric](18, 0) NULL,
	[IC_MdlCode] [varchar](25) NULL,
 CONSTRAINT [PK_WMS_RCICPR] PRIMARY KEY CLUSTERED 
(
	[IC_TransNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_SPLCRM]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_SPLCRM](
	[SPLC_PSN] [varchar](100) NULL,
	[SPLC_CAT] [varchar](20) NULL,
	[SPLC_LINE] [varchar](20) NULL,
	[SPLC_FED] [varchar](20) NULL,
	[SPLC_ITMCD] [varchar](30) NULL,
	[SPLC_LOTNO] [varchar](50) NULL,
	[SPLC_QTY] [varchar](20) NULL,
	[SPLC_TRANSCNO] [varchar](50) NULL,
	[SPLC_LUPDATE] [datetime] NULL,
	[SPLC_ID] [varchar](50) NULL,
	[SPLC_LOTNO1] [varchar](50) NULL,
	[SPLC_QTY1] [varchar](50) NULL,
	[SPLC_LOTNO2] [varchar](50) NULL,
	[SPLC_QTY2] [varchar](50) NULL,
	[SPLC_LOTNO3] [varchar](50) NULL,
	[SPLC_QTY3] [varchar](50) NULL,
	[SPLC_LOTNO4] [varchar](50) NULL,
	[SPLC_QTY4] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_SWMP_HIS]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_SWMP_HIS](
	[SWMP_WONO] [char](25) NOT NULL,
	[SWMP_PROCD] [char](10) NOT NULL,
	[SWMP_LINENO] [char](10) NOT NULL,
	[SWMP_MCMCZITM] [char](40) NOT NULL,
	[SWMP_LINE] [int] IDENTITY(1,1) NOT NULL,
	[SWMP_ITMCD] [char](25) NULL,
	[SWMP_LOTNO] [char](25) NULL,
	[SWMP_LUPDT] [datetime] NULL,
	[SWMP_LUPBY] [varchar](100) NULL,
	[SWMP_MC] [varchar](100) NULL,
	[SWMP_MCZ] [varchar](50) NULL,
	[SWMP_MDLCD] [varchar](100) NULL,
	[SWMP_BOMRV] [varchar](20) NULL,
	[SWMP_QTY] [numeric](18, 0) NULL,
	[SWMP_SPID] [varchar](50) NULL,
	[SWMP_UNQ] [varchar](50) NULL,
	[SWMP_PSNNO] [varchar](100) NULL,
	[SWMP_JOBNO] [varchar](100) NULL,
	[SWMP_MAINITMCD] [varchar](100) NULL,
	[SWMP_SUBITMCD] [varchar](100) NULL,
	[SWMP_REMARK] [varchar](100) NULL,
	[SWMP_Judge] [varchar](100) NULL,
	[SWMP_Bal] [varchar](20) NULL,
	[swmp_act] [varchar](10) NULL,
	[SWMP_CLS] [numeric](18, 0) NULL,
	[SWMP_Fdrno] [varchar](20) NULL,
	[SWMP_TTLCLS] [numeric](18, 0) NULL,
 CONSTRAINT [PK_WMS_SWMP_HIS] PRIMARY KEY CLUSTERED 
(
	[SWMP_WONO] ASC,
	[SWMP_PROCD] ASC,
	[SWMP_LINENO] ASC,
	[SWMP_MCMCZITM] ASC,
	[SWMP_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 99) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_SWPS_HIS]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_SWPS_HIS](
	[SWPS_WONO] [char](25) NOT NULL,
	[SWPS_PROCD] [char](10) NOT NULL,
	[SWPS_LINENO] [char](10) NOT NULL,
	[SWPS_MCMCZITM] [char](40) NOT NULL,
	[SWPS_LINE] [int] IDENTITY(1,1) NOT NULL,
	[SWPS_ITMCD] [char](25) NULL,
	[SWPS_LOTNO] [char](25) NULL,
	[SWPS_NITMCD] [char](25) NULL,
	[SWPS_NLOTNO] [char](25) NULL,
	[SWPS_REMQT] [int] NULL,
	[SWPS_LUPDT] [datetime] NULL,
	[SWPS_LUPBY] [varchar](100) NULL,
	[SWPS_REMARK] [varchar](50) NULL,
	[QTY] [numeric](18, 0) NULL,
	[NQTY] [numeric](18, 0) NULL,
	[SWPS_UNQ] [varchar](50) NULL,
	[SWPS_NUNQ] [varchar](50) NULL,
	[SWPS_PSNNO] [varchar](50) NULL,
	[SWPS_JOBNO] [varchar](50) NULL,
	[SWPS_SPID] [varchar](50) NULL,
	[SWPS_MC] [varchar](50) NULL,
	[SWPS_MCZ] [varchar](50) NULL,
	[SWPS_MDLCD] [varchar](100) NULL,
	[SWPS_BOMRV] [varchar](50) NULL,
	[SWPS_MAINITMCD] [varchar](50) NULL,
	[SWPS_SUBITMCD] [varchar](50) NULL,
	[SWPS_JUDGE] [varchar](100) NULL,
	[SWPS_Bal] [varchar](20) NULL,
	[SWPS_VITMCD] [varchar](50) NULL,
	[SWPS_VLOTNO] [varchar](100) NULL,
	[SWPS_VUNQ] [varchar](100) NULL,
	[SWPS_VQTY] [numeric](18, 0) NULL,
	[SWPS_Fdrno] [varchar](20) NULL,
 CONSTRAINT [PK_WMS_SWPS_HIS] PRIMARY KEY CLUSTERED 
(
	[SWPS_WONO] ASC,
	[SWPS_PROCD] ASC,
	[SWPS_LINENO] ASC,
	[SWPS_MCMCZITM] ASC,
	[SWPS_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_TLWS_LOG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_TLWS_LOG](
	[TLWS_SPID] [varchar](50) NULL,
	[TLWS_PSNNO] [varchar](100) NULL,
	[TLWS_WONO] [varchar](100) NULL,
	[TLWS_PROCD] [varchar](20) NULL,
	[TLWS_LINENO] [varchar](20) NULL,
	[TLWS_JOBNO] [varchar](50) NULL,
	[TLWS_STSFG] [varchar](50) NULL,
	[TLWS_LUPDT] [datetime] NULL,
	[TLWS_LUPBY] [varchar](50) NULL,
	[TLWS_MDLCD] [varchar](50) NULL,
	[TLWS_BOMRV] [varchar](20) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_TLWS_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_TLWS_TBL](
	[TLWS_PSNNO] [varchar](100) NULL,
	[TLWS_WONO] [varchar](100) NULL,
	[TLWS_PROCD] [varchar](20) NULL,
	[TLWS_LINENO] [varchar](20) NULL,
	[TLWS_JOBNO] [varchar](50) NULL,
	[TLWS_STSFG] [varchar](50) NULL,
	[TLWS_LUPDT] [datetime] NULL,
	[TLWS_LUPBY] [varchar](50) NULL,
	[TLWS_MDLCD] [varchar](50) NULL,
	[TLWS_BOMRV] [varchar](20) NULL,
	[TLWS_SPID] [varchar](50) NULL,
	[TLWS_Status] [varchar](50) NULL,
	[TLWS_lupdt_act] [datetime] NULL,
	[TLWS_lupdt_com] [datetime] NULL,
	[TLWS_LUPBY_ACT] [varchar](100) NULL,
	[TLWS_LUPBY_COM] [varchar](100) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_Trace_log]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_Trace_log](
	[Trace_PSNNo] [varchar](50) NULL,
	[Trace_WONo] [varchar](50) NULL,
	[Trace_Procd] [varchar](20) NULL,
	[Trace_LineNo] [varchar](50) NULL,
	[Trace_Mdlcd] [varchar](50) NULL,
	[Trace_JobNo] [varchar](50) NULL,
	[Trace_Itmcd] [varchar](50) NULL,
	[Trace_LotNo] [varchar](50) NULL,
	[Trace_Qty] [numeric](18, 0) NULL,
	[Trace_Unq] [varchar](50) NULL,
	[Trace_Remark] [varchar](50) NULL,
	[Trace_Pic] [varchar](50) NULL,
	[Trace_Lupdt] [datetime] NULL,
	[Trace_Status] [varchar](50) NULL,
	[Trace_Mc] [varchar](50) NULL,
	[Trace_Bomrv] [varchar](50) NULL,
	[Trace_mcz] [varchar](50) NULL,
	[Trace_Mainitmcd] [varchar](50) NULL,
	[Trace_subitmcd] [varchar](50) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_Trace_Prep]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_Trace_Prep](
	[Trace_PSNNo] [varchar](50) NULL,
	[Trace_WONo] [varchar](50) NULL,
	[Trace_Procd] [varchar](20) NULL,
	[Trace_LineNo] [varchar](50) NULL,
	[Trace_Mdlcd] [varchar](50) NULL,
	[Trace_JobNo] [varchar](50) NULL,
	[Trace_Itmcd] [varchar](50) NULL,
	[Trace_LotNo] [varchar](50) NULL,
	[Trace_Qty] [numeric](18, 0) NULL,
	[Trace_Unq] [varchar](50) NULL,
	[Trace_Remark] [varchar](50) NULL,
	[Trace_Pic] [varchar](50) NULL,
	[Trace_Lupdt] [datetime] NULL,
	[Trace_Status] [varchar](50) NULL,
	[Trace_Mc] [varchar](50) NULL,
	[Trace_mcz] [varchar](50) NULL,
	[Trace_Subitmcd] [varchar](50) NULL,
	[Trace_Mainitmcd] [varchar](50) NULL,
	[Trace_Bomrv] [varchar](20) NULL,
	[TRACE_SPID] [varchar](30) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WMS_TWMP_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WMS_TWMP_TBL](
	[TWMP_WONO] [char](25) NOT NULL,
	[TWMP_PROCD] [char](10) NOT NULL,
	[TWMP_LINENO] [char](10) NOT NULL,
	[TWMP_MCMCZITM] [char](40) NOT NULL,
	[TWMP_LOCCD] [char](10) NOT NULL,
	[TWMP_P1] [char](1) NULL,
	[TWMP_P2] [char](1) NULL,
	[TWMP_MC] [char](10) NULL,
	[TWMP_MCZ] [char](10) NULL,
	[TWMP_ITMCD] [char](25) NULL,
	[TWMP_TQTY] [int] NULL,
	[TWMP_QTY] [int] NULL,
	[TWMP_CURRLOTNO] [char](25) NULL,
	[TWMP_PREVLOTNO] [char](25) NULL,
	[TWMP_PREVITMCD] [char](25) NULL,
	[TWMP_LSWSN] [char](25) NULL,
	[TWMP_LSWTM] [datetime] NULL,
	[TWMP_MIXFG] [char](1) NULL,
	[TWMP_MIXLOT] [int] NULL,
	[TWMP_REMQT] [int] NULL,
	[TWMP_COUNTER] [int] NULL,
	[TWMP_NLOTNO] [char](25) NULL,
	[TWMP_NITMCD] [char](25) NULL,
	[TWMP_LUPDT] [datetime] NULL,
	[TWMP_LUPBY] [char](12) NULL,
 CONSTRAINT [PK_WMS_TWMP_TBL] PRIMARY KEY CLUSTERED 
(
	[TWMP_WONO] ASC,
	[TWMP_PROCD] ASC,
	[TWMP_LINENO] ASC,
	[TWMP_MCMCZITM] ASC,
	[TWMP_LOCCD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[WOH_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[WOH_TBL](
	[WOH_CD] [varchar](50) NOT NULL,
	[WOH_TTLUSE] [int] NULL,
	[WOH_CREATEDAT] [datetime] NULL,
	[WOH_LUPDT] [datetime] NULL,
 CONSTRAINT [PK_WOH_TBL] PRIMARY KEY CLUSTERED 
(
	[WOH_CD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[ZCARANG_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[ZCARANG_TBL](
	[KODE_CARA_ANGKUT] [varchar](2) NOT NULL,
	[URAIAN_CARA_ANGKUT] [varchar](50) NULL,
 CONSTRAINT [PK_ZCARANG_TBL] PRIMARY KEY CLUSTERED 
(
	[KODE_CARA_ANGKUT] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[ZJNSTPB_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[ZJNSTPB_TBL](
	[KODE_JENIS_TPB] [char](2) NOT NULL,
	[URAIAN_JENIS_TPB] [varchar](50) NULL,
	[SINGKATAN] [varchar](10) NULL,
 CONSTRAINT [PK_MTPB_TBL] PRIMARY KEY CLUSTERED 
(
	[KODE_JENIS_TPB] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[ZKANTOR_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[ZKANTOR_TBL](
	[ID] [bigint] NOT NULL,
	[KODE_KANTOR] [varchar](50) NULL,
	[URAIAN_KANTOR] [varchar](50) NULL,
 CONSTRAINT [PK_ZKANTOR_TBL] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[ZREFERENSI_KEMASAN_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[ZREFERENSI_KEMASAN_TBL](
	[ID] [bigint] NOT NULL,
	[KODE_KEMASAN] [varchar](255) NULL,
	[URAIAN_KEMASAN] [varchar](255) NULL,
 CONSTRAINT [PK_ZREFERENSI_KEMASAN] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[ZTJNKIR_TBL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[ZTJNKIR_TBL](
	[ID] [bigint] NOT NULL,
	[KODE_DOKUMEN] [varchar](50) NULL,
	[KODE_TUJUAN_PENGIRIMAN] [varchar](50) NULL,
	[URAIAN_TUJUAN_PENGIRIMAN] [varchar](120) NULL,
 CONSTRAINT [PK_REF_TUJUAN_PENGIRIMAN_TBL] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_DLVSO]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCI_DLVSO] ON [dbo].[DLVSO_TBL]
(
	[DLVSO_DLVID] ASC,
	[DLVSO_ITMCD] ASC,
	[DLVSO_QTY] ASC,
	[DLVSO_CPONO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_FIFORM_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCI_FIFORM_IDX] ON [dbo].[FIFORM_TBL]
(
	[FIFORM_DT] ASC,
	[FIFORM_BCTYPE] ASC,
	[FIFORM_NOAJU] ASC,
	[FIFORM_NOPEN] ASC,
	[FIFORM_ITMCD] ASC,
	[FIFORM_TM] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [ITH_TBL_IDX2]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [ITH_TBL_IDX2] ON [dbo].[ITH_TBL]
(
	[ITH_SER] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_ITH_FORM]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [NCI_ITH_FORM] ON [dbo].[ITH_TBL]
(
	[ITH_FORM] ASC,
	[ITH_LUPDT] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_ITH_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCI_ITH_IDX] ON [dbo].[ITH_TBL]
(
	[ITH_ITMCD] ASC,
	[ITH_DATE] ASC,
	[ITH_FORM] ASC,
	[ITH_DOC] ASC,
	[ITH_WH] ASC,
	[ITH_SER] ASC,
	[ITH_REMARK] ASC,
	[ITH_LOC] ASC,
	[ITH_LINE] ASC,
	[ITH_LUPDT] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_ITH_REMARK]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [NCI_ITH_REMARK] ON [dbo].[ITH_TBL]
(
	[ITH_REMARK] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCIITMLOC]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCIITMLOC] ON [dbo].[ITMLOC_TBL]
(
	[ITMLOC_ITM] ASC,
	[ITMLOC_LOCG] ASC,
	[ITMLOC_LOC] ASC,
	[ITMLOC_BG] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [jobs_queue_index]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [jobs_queue_index] ON [dbo].[jobs]
(
	[queue] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [MDEL_TBL_MDEL_DELCD_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [MDEL_TBL_MDEL_DELCD_IDX] ON [dbo].[MDEL_TBL]
(
	[MDEL_DELCD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCIMITMD]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [NCIMITMD] ON [dbo].[MITM_TBL]
(
	[MITM_MODEL] ASC,
	[MITM_ITMCD] ASC,
	[MITM_STKUOM] ASC,
	[MITM_ITMD1] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [MITMGRP_TBL_MITMGRP_ITMCD_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [MITMGRP_TBL_MITMGRP_ITMCD_IDX] ON [dbo].[MITMGRP_TBL]
(
	[MITMGRP_ITMCD_GRD] ASC,
	[MITMGRP_ITMCD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NonClusteredIndex-20220616-200847]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [NonClusteredIndex-20220616-200847] ON [dbo].[MITMGRP_TBL]
(
	[MITMGRP_ITMCD] ASC,
	[MITMGRP_ITMCD_GRD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [queue_monitor_table_failed_index]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [queue_monitor_table_failed_index] ON [dbo].[monitors]
(
	[failed] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [queue_monitor_table_job_id_index]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [queue_monitor_table_job_id_index] ON [dbo].[monitors]
(
	[job_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [queue_monitor_table_started_at_index]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [queue_monitor_table_started_at_index] ON [dbo].[monitors]
(
	[started_at] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [queue_monitor_table_time_elapsed_index]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [queue_monitor_table_time_elapsed_index] ON [dbo].[monitors]
(
	[time_elapsed] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [oauth_access_tokens_user_id_index]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [oauth_access_tokens_user_id_index] ON [dbo].[oauth_access_tokens]
(
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [oauth_clients_user_id_index]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [oauth_clients_user_id_index] ON [dbo].[oauth_clients]
(
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [oauth_personal_access_clients_client_id_index]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [oauth_personal_access_clients_client_id_index] ON [dbo].[oauth_personal_access_clients]
(
	[client_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [oauth_refresh_tokens_access_token_id_index]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [oauth_refresh_tokens_access_token_id_index] ON [dbo].[oauth_refresh_tokens]
(
	[access_token_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [personal_access_tokens_token_unique]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [personal_access_tokens_token_unique] ON [dbo].[personal_access_tokens]
(
	[token] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [personal_access_tokens_tokenable_type_tokenable_id_index]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [personal_access_tokens_tokenable_type_tokenable_id_index] ON [dbo].[personal_access_tokens]
(
	[tokenable_type] ASC,
	[tokenable_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCIPND_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCIPND_IDX] ON [dbo].[PND_TBL]
(
	[PND_DOC] ASC,
	[PND_ITMCD] ASC,
	[PND_ITMLOT] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_RCV_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCI_RCV_IDX] ON [dbo].[RCV_TBL]
(
	[RCV_PO] ASC,
	[RCV_INVNO] ASC,
	[RCV_ITMCD] ASC,
	[RCV_DONO] ASC,
	[RCV_GRLNO] ASC,
	[RCV_RPNO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [RCV_TBL_RCV_BCTYPE_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [RCV_TBL_RCV_BCTYPE_IDX] ON [dbo].[RCV_TBL]
(
	[RCV_BCTYPE] ASC,
	[RCV_DONO] ASC,
	[RCV_ZSTSRCV] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [RCV_TBL_RCV_ITMCD_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [RCV_TBL_RCV_ITMCD_IDX] ON [dbo].[RCV_TBL]
(
	[RCV_ITMCD] ASC,
	[RCV_ITMCD_REFF] ASC,
	[RCV_BCDATE] ASC,
	[RCV_BCNO] ASC,
	[RCV_DONO] ASC,
	[RCV_DONO_REFF] ASC,
	[RCV_RPNO] ASC,
	[RCV_BCTYPE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [RCV_TBL_RCV_ITMCD_MUTASI_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [RCV_TBL_RCV_ITMCD_MUTASI_IDX] ON [dbo].[RCV_TBL]
(
	[RCV_ITMCD] ASC,
	[RCV_BCDATE] ASC,
	[RCV_DONO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCIRCVFGSCN]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [NCIRCVFGSCN] ON [dbo].[RCVFGSCN_TBL]
(
	[RCVFGSCN_LOC] ASC,
	[RCVFGSCN_SER] ASC,
	[RCVFGSCN_USRID] ASC,
	[RCVFGSCN_SAVED] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_RCVSCN_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCI_RCVSCN_IDX] ON [dbo].[RCVSCN_TBL]
(
	[RCVSCN_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [RCVSCN_TBL_RCVSCN_DONO_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [RCVSCN_TBL_RCVSCN_DONO_IDX] ON [dbo].[RCVSCN_TBL]
(
	[RCVSCN_DONO] ASC,
	[RCVSCN_ITMCD] ASC,
	[RCVSCN_LOTNO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCISCR_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCISCR_IDX] ON [dbo].[SCR_TBL]
(
	[SCR_DOC] ASC,
	[SCR_ITMCD] ASC,
	[SCR_ITMLOT] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_SER]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [NCI_SER] ON [dbo].[SER_TBL]
(
	[SER_DOC] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCISERD]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCISERD] ON [dbo].[SERD_TBL]
(
	[SERD_PSNNO] ASC,
	[SERD_LINENO] ASC,
	[SERD_CAT] ASC,
	[SERD_FR] ASC,
	[SERD_PROCD] ASC,
	[SERD_JOB] ASC,
	[SERD_QTPER] ASC,
	[SERD_MC] ASC,
	[SERD_MCZ] ASC,
	[SERD_ITMCD] ASC,
	[SERD_QTYREQ] ASC,
	[SERD_QTY] ASC,
	[SERD_LOTNO] ASC,
	[SERD_MSFLG] ASC,
	[SERD_MSCANTM] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [SERD_TBL_SERD_JOB_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [SERD_TBL_SERD_JOB_IDX] ON [dbo].[SERD_TBL]
(
	[SERD_JOB] ASC,
	[SERD_ITMCD] ASC,
	[SERD_PSNNO] ASC,
	[SERD_LINENO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_SERD2]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCI_SERD2] ON [dbo].[SERD2_TBL]
(
	[SERD2_PSNNO] ASC,
	[SERD2_LINENO] ASC,
	[SERD2_PROCD] ASC,
	[SERD2_CAT] ASC,
	[SERD2_FR] ASC,
	[SERD2_SER] ASC,
	[SERD2_JOB] ASC,
	[SERD2_QTPER] ASC,
	[SERD2_MC] ASC,
	[SERD2_MCZ] ASC,
	[SERD2_ITMCD] ASC,
	[SERD2_QTY] ASC,
	[SERD2_LOTNO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_SERD2_SER]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [NCI_SERD2_SER] ON [dbo].[SERD2_TBL]
(
	[SERD2_SER] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [SERD2_TBL_SERD2_SER_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [SERD2_TBL_SERD2_SER_IDX] ON [dbo].[SERD2_TBL]
(
	[SERD2_SER] ASC,
	[SERD2_JOB] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCISERRC]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCISERRC] ON [dbo].[SERRC_TBL]
(
	[SERRC_SER] ASC,
	[SERRC_LINE] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_SI_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCI_SI_IDX] ON [dbo].[SI_TBL]
(
	[SI_DOC] ASC,
	[SI_DOCREFF] ASC,
	[SI_CUSCD] ASC,
	[SI_ITMCD] ASC,
	[SI_LINENO] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_SPL_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCI_SPL_IDX] ON [dbo].[SPL_TBL]
(
	[SPL_DOC] ASC,
	[SPL_CAT] ASC,
	[SPL_LINE] ASC,
	[SPL_FEDR] ASC,
	[SPL_RMRK] ASC,
	[SPL_ORDERNO] ASC,
	[SPL_RACKNO] ASC,
	[SPL_ITMCD] ASC,
	[SPL_MS] ASC,
	[SPL_QTYUSE] ASC,
	[SPL_DOCNO] ASC,
	[SPL_MC] ASC,
	[SPL_PROCD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_SPLSCN_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCI_SPLSCN_IDX] ON [dbo].[SPLSCN_TBL]
(
	[SPLSCN_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_SPLSCN_IDX2]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [NCI_SPLSCN_IDX2] ON [dbo].[SPLSCN_TBL]
(
	[SPLSCN_DOC] ASC,
	[SPLSCN_CAT] ASC,
	[SPLSCN_LINE] ASC,
	[SPLSCN_FEDR] ASC,
	[SPLSCN_ORDERNO] ASC,
	[SPLSCN_ITMCD] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_TXROUTE_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [NCI_TXROUTE_IDX] ON [dbo].[TXROUTE_TBL]
(
	[TXROUTE_ID] ASC,
	[TXROUTE_WH] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [users_email_unique]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE UNIQUE NONCLUSTERED INDEX [users_email_unique] ON [dbo].[users]
(
	[email] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, IGNORE_DUP_KEY = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCIINVRM]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [NCIINVRM] ON [dbo].[WMS_InvRM]
(
	[cPartCode] ASC,
	[cLotNo] ASC,
	[cLoc] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [NCI_PPSN2LOG]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [NCI_PPSN2LOG] ON [dbo].[WMS_PPSN2_LOG]
(
	[PPSN2_PSNNO] ASC,
	[PPSN2_PROCD] ASC,
	[PPSN2_MC] ASC,
	[PPSN2_MCZ] ASC,
	[PPSN2_SUBPN] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [ZTJNKIR_TBL_KODE_DOKUMEN_IDX]    Script Date: 2025-04-28 7:54:03 AM ******/
CREATE NONCLUSTERED INDEX [ZTJNKIR_TBL_KODE_DOKUMEN_IDX] ON [dbo].[ZTJNKIR_TBL]
(
	[KODE_DOKUMEN] ASC,
	[KODE_TUJUAN_PENGIRIMAN] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [CERTIFICATE]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [ID_MODUL]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [KODE_GUDANG]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [KPPBC]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [NAMA_PENGUSAHA]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [NOMOR_SKEP]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [NPWP]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [PASSWORD]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [PASSWORD_CERTIFICATE]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [PORT]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [TANGGAL_SKEP]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [URL]
GO
ALTER TABLE [dbo].[aktivasi_aplikasi] ADD  DEFAULT (NULL) FOR [USERNAME]
GO
ALTER TABLE [dbo].[failed_jobs] ADD  DEFAULT (getdate()) FOR [failed_at]
GO
ALTER TABLE [dbo].[MCUS_TBL] ADD  CONSTRAINT [DF__MCUS_TBL__MCUS_N__477199F1]  DEFAULT ((1)) FOR [MCUS_NOSIGN]
GO
ALTER TABLE [dbo].[MITMSA_TBL] ADD  DEFAULT (NULL) FOR [MITMSA_RELATION_TYPE]
GO
ALTER TABLE [dbo].[MITMSA_TBL] ADD  DEFAULT (NULL) FOR [MITMSA_LOC_AT_PCB]
GO
ALTER TABLE [dbo].[monitors] ADD  DEFAULT ('0') FOR [failed]
GO
ALTER TABLE [dbo].[monitors] ADD  DEFAULT ('0') FOR [attempt]
GO
ALTER TABLE [dbo].[PROCMSTR_TBL] ADD  DEFAULT ((0)) FOR [PROCMSTR_ISPROCESS]
GO
ALTER TABLE [dbo].[WMS_Inv] ADD  DEFAULT (NULL) FOR [updated_by]
GO
ALTER TABLE [dbo].[WMS_TWMP_TBL] ADD  CONSTRAINT [DF_WMS_TWMP_TBL_TWMP_MIXLOT]  DEFAULT ((0)) FOR [TWMP_MIXLOT]
GO
ALTER TABLE [dbo].[WMS_TWMP_TBL] ADD  CONSTRAINT [DF_TWMS_TWMP_TBL_TWMP_REMQT]  DEFAULT ((0)) FOR [TWMP_REMQT]
GO
ALTER TABLE [dbo].[WMS_TWMP_TBL] ADD  CONSTRAINT [DF_WMS_TWMP_TBL_TWMP_COUNTER]  DEFAULT ((0)) FOR [TWMP_COUNTER]
GO
ALTER TABLE [dbo].[EMPACCESS_TBL]  WITH CHECK ADD  CONSTRAINT [FK_EMPACCESS_TBL_MENU_TBL] FOREIGN KEY([EMPACCESS_MENUID])
REFERENCES [dbo].[MENU_TBL] ([MENU_ID])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[EMPACCESS_TBL] CHECK CONSTRAINT [FK_EMPACCESS_TBL_MENU_TBL]
GO
ALTER TABLE [dbo].[MSTEMP_TBL]  WITH CHECK ADD  CONSTRAINT [FK_MSTEMP_TBL_MSTGRP_TBL] FOREIGN KEY([MSTEMP_GRP])
REFERENCES [dbo].[MSTGRP_TBL] ([MSTGRP_ID])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[MSTEMP_TBL] CHECK CONSTRAINT [FK_MSTEMP_TBL_MSTGRP_TBL]
GO
ALTER TABLE [dbo].[MSTLOC_TBL]  WITH CHECK ADD  CONSTRAINT [FK_MSTLOC_TBL_MSTLOCG_TBL] FOREIGN KEY([MSTLOC_GRP])
REFERENCES [dbo].[MSTLOCG_TBL] ([MSTLOCG_ID])
GO
ALTER TABLE [dbo].[MSTLOC_TBL] CHECK CONSTRAINT [FK_MSTLOC_TBL_MSTLOCG_TBL]
GO
ALTER TABLE [dbo].[transfer_indirect_rm_details]  WITH CHECK ADD  CONSTRAINT [transfer_indirect_rm_details_id_header_foreign] FOREIGN KEY([id_header])
REFERENCES [dbo].[transfer_indirect_rm_headers] ([id])
GO
ALTER TABLE [dbo].[transfer_indirect_rm_details] CHECK CONSTRAINT [transfer_indirect_rm_details_id_header_foreign]
GO
ALTER TABLE [dbo].[TRFD_TBL]  WITH CHECK ADD  CONSTRAINT [FK_TRFD_TBL_TRFH_TBL] FOREIGN KEY([TRFD_DOC])
REFERENCES [dbo].[TRFH_TBL] ([TRFH_DOC])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[TRFD_TBL] CHECK CONSTRAINT [FK_TRFD_TBL_TRFH_TBL]
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_CancelFinishGood]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[sato_sp_CancelFinishGood]
	@ScanSI	[varchar](100),
	@DocReff 	[varchar](50),
	@LineNo 	[varchar](50),
	@status [varchar] (50)
AS
BEGIN
	SET NOCOUNT ON;

IF (@status = 'Check')
BEGIN
SELECT * FROM SISCN_TBL WHERE SISCN_DOC=@ScanSI AND SISCN_LINENO=@LineNo
AND SISCN_DOCREFF=@DocReff AND SISCN_PLLT IS NULL
END 

ELSE IF(@status='Insert')
BEGIN
DELETE FROM SISCN_TBL WHERE SISCN_DOC=@ScanSI AND SISCN_LINENO=@LineNo
AND SISCN_DOCREFF=@DocReff AND SISCN_PLLT IS NULL
END
END


/****** Object:  StoredProcedure [dbo].[CheckITHPSN]    Script Date: 2020-02-26 08:58:00 ******/
SET ANSI_NULLS ON


GO
/****** Object:  StoredProcedure [dbo].[sato_sp_CekQTY]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[sato_sp_CekQTY]
	@Donoscan 	[varchar](100),
	@loc 	[varchar](50),
	@Item 	[varchar](50)
AS
BEGIN
	SET NOCOUNT ON;

SELECT a.[RCV_ITMCD],a.[RCV_DONO],a.[RCV_QTY],sum(c.[RCVSCN_QTY]) AS RCVSCN_QTY,b.[ITMLOC_LOC],b.[ITMLOC_USRID]
FROM [dbo].[RCV_TBL] a
inner join [dbo].[ITMLOC_TBL] b ON (a.[RCV_ITMCD] = b.[ITMLOC_ITM])
inner join [dbo].[RCVSCN_TBL] c ON (c.[RCVSCN_DONO] = a.[RCV_DONO] AND b.[ITMLOC_ITM] = c.[RCVSCN_ITMCD])
WHERE a.[RCV_DONO] = @Donoscan AND b.[ITMLOC_LOC] = @loc AND a.[RCV_ITMCD] = @Item
GROUP BY a.[RCV_ITMCD],a.[RCV_DONO],a.[RCV_QTY],b.[ITMLOC_LOC],b.[ITMLOC_USRID]


END



GO
/****** Object:  StoredProcedure [dbo].[sato_sp_check_ith_rcvfg]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sato_sp_check_ith_rcvfg]
@serial varchar(50)
AS
BEGIN
	SET NOCOUNT ON;


	SELECT ITH_SER,SUM(ITH_QTY) ITH_QTY FROM ITH_TBL where ITH_WH='ARQA1' and ITH_SER=@serial
 GROUP BY ITH_SER HAVING SUM(ITH_QTY)>0


END

GO
/****** Object:  StoredProcedure [dbo].[sato_sp_CHECKITHEXISTSVSRCV]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[sato_sp_CHECKITHEXISTSVSRCV]
	@Donoscan 	[varchar](100),
	@itm		[varchar](50)
AS
BEGIN
	SET NOCOUNT ON;



SELECT a.ITH_DOC,a.ITH_ITMCD
from ITH_TBL a left join RCV_TBL b on (a.ITH_DOC=b.RCV_DONO AND a.ITH_ITMCD = b.RCV_ITMCD)
where a.ITH_DOC =@Donoscan AND a.ITH_ITMCD='LOREMIPSUM'  group by a.ITH_DOC,a.ITH_ITMCD having sum(a.ITH_QTY) >= sum(b.RCV_QTY) ;

--SELECT a.ITH_DOC,a.ITH_ITMCD
--from ITH_TBL a left join RCV_TBL b on (a.ITH_DOC=b.RCV_DONO AND a.ITH_ITMCD = b.RCV_ITMCD)
--where a.ITH_DOC =@Donoscan AND a.ITH_ITMCD=@itm  group by a.ITH_DOC,a.ITH_ITMCD having sum(a.ITH_QTY) >= sum(b.RCV_QTY) ;


END



/****** Object:  StoredProcedure [dbo].[SaveToITH]    Script Date: 18/02/2020 09:18:27 ******/
SET ANSI_NULLS ON


GO
/****** Object:  StoredProcedure [dbo].[sato_sp_CheckITHFinishGood]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


	CREATE PROCEDURE [dbo].[sato_sp_CheckITHFinishGood]
	@ScanSI	[varchar](100),
	@DocReff 	[varchar](50),
	@LineNo 	[varchar](50)
	
AS
BEGIN
	SET NOCOUNT ON;


SELECT A.SISCN_DOC,a.SISCN_LINENO,a.SISCN_DOCREFF, SUM(a.SISCN_SERQTY) SISCN_QTY,z.total as SI_QTY 
FROM SISCN_TBL a inner join SI_TBL b on (b.SI_DOC=a.SISCN_DOC AND b.SI_DOCREFF=a.SISCN_DOCREFF AND a.SISCN_LINENO=b.SI_LINENO)
inner join (SELECT SI_DOC,SI_ITMCD,SI_LINENO,SI_DOCREFF,SUM(SI_QTY) as Total
from SI_TBL
where SI_DOC =@ScanSI AND SI_DOCREFF=@DocReff AND SI_LINENO=@LineNo group by SI_DOC,SI_ITMCD,SI_LINENO,SI_DOCREFF)
z ON a.SISCN_DOC=z.SI_DOC and a.SISCN_DOCREFF=z.SI_DOCREFF AND a.SISCN_LINENO=z.SI_LINENO AND SISCN_PLLT IS NOT NULL
 where a.SISCN_DOC=@ScanSI AND a.SISCN_DOCREFF=@DocReff AND a.SISCN_LINENO=@LineNo  AND SISCN_PLLT IS NOT NULL
 group by a.SISCN_DOC,SISCN_LINENO,a.SISCN_DOCREFF,z.Total
having SUM(A.SISCN_SERQTY)>=z.Total

END







GO
/****** Object:  StoredProcedure [dbo].[sato_sp_CheckITHPSN]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[sato_sp_CheckITHPSN]
	
	@SPL_DOC	[varchar](100),
	@splscn_cat [varchar](50),
	@splscn_line [varchar](50),
	@splscn_fidr [varchar](50),
	@SPL_ITMCD 	[varchar](50),
	@SPL_ORDERNO 	[varchar](50)
	
AS
BEGIN
	SET NOCOUNT ON;

SELECT a.SPLSCN_DOC,a.SPLSCN_ITMCD,a.SPLSCN_ORDERNO ,b.SPL_QTYREQ,SUM(a.SPLSCN_QTY) AS SPLSCN_QTY
 FROM SPLSCN_TBL a 
 INNER JOIN (SELECT  SPL_DOC,SPL_ITMCD,SPL_ORDERNO ,SUM(SPL_QTYREQ) SPL_QTYREQ FROM SPL_TBL 
 WHERE SPL_DOC=@SPL_DOC and spl_CAT=@splscn_cat AND spl_LINE=@splscn_line AND SPL_FEDR=@splscn_fidr
 AND SPL_ITMCD=@SPL_ITMCD AND SPL_ORDERNO=@SPL_ORDERNO GROUP BY SPL_DOC,SPL_ITMCD,SPL_ORDERNO) AS b
 ON a.SPLSCN_DOC=b.SPL_DOC AND a.SPLSCN_ITMCD=b.SPL_ITMCD AND a.SPLSCN_ORDERNO=b.SPL_ORDERNO
 where a.SPLSCN_DOC=@SPL_DOC and a.SPLSCN_CAT=@splscn_cat AND a.SPLSCN_LINE=@splscn_line AND a.SPLSCN_FEDR=@splscn_fidr
 AND a.SPLSCN_ITMCD=@SPL_ITMCD AND a.SPLSCN_ORDERNO=@SPL_ORDERNO and SPLSCN_SAVED='1'
 GROUP BY a.SPLSCN_DOC,a.SPLSCN_ITMCD,a.SPLSCN_ORDERNO,b.SPL_QTYREQ
 HAVING b.SPL_QTYREQ <= sum(a.SPLSCN_QTY)


END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_COMPAREITEMNLOC]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[sato_sp_COMPAREITEMNLOC]
	@ITMNO	[varchar](50),
	@ITMLOC	[varchar](50)
AS
BEGIN
	SET NOCOUNT ON;

Select 		
			ITMLOC_ITM AS 'ITMLOC_ITM',
			ITMLOC_LOC	AS	'ITMLOC_LOC'
		
From	ITMLOC_TBL
WHERE 
(
	ITMLOC_ITM=@ITMNO and ITMLOC_LOC = @ITMLOC
)
END






GO
/****** Object:  StoredProcedure [dbo].[sato_sp_DELETE_USER_HT]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[sato_sp_DELETE_USER_HT]
       @userid                      VARCHAR(10)  = NULL   
      
	 

AS 
BEGIN 
     SET NOCOUNT ON 
	  
	 DELETE [dbo].[User_ht]  
    WHERE UserId = @userid

end






GO
/****** Object:  StoredProcedure [dbo].[sato_sp_get_location]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE procedure [dbo].[sato_sp_get_location]
@cdloc varchar(50),
@txroute varchar(50),
@status varchar(50)
AS
BEGIN
	SET NOCOUNT ON;

IF (@status ='getLocation')
BEGIN
SELECT * FROM [MSTLOCG_TBL] WHERE  [MSTLOCG_ID]=@cdloc AND [MSTLOCG_ID] 
IN(select TXROUTE_WH from TXROUTE_TBL WHERE TXROUTE_ID=@txroute)

END 
END




GO
/****** Object:  StoredProcedure [dbo].[sato_sp_get_siscn_outfg]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[sato_sp_get_siscn_outfg]

@scanSI [varchar](50),
@siscndocreff [varchar] (50),
@silineno [varchar](50),
@dt      [varchar](50),
@status [varchar](50)

AS
BEGIN
	SET NOCOUNT ON;

if (@status = 'getsiscn')
BEGIN
SELECT SISCN_DOC,SISCN_SER,SISCN_SERQTY,SISCN_LUPDT,SISCN_USRID FROM SISCN_TBL
WHERE SISCN_DOCREFF=@siscndocreff AND SISCN_DOC=@scanSI
AND SISCN_LINENO=@silineno AND SISCN_PLLT IS NULL
GROUP BY SISCN_DOC,SISCN_SER,SISCN_SERQTY,SISCN_LUPDT,SISCN_USRID;
END

ELSE IF (@status = 'getcountitemSI')
BEGIN
SELECT SI_DOC,SI_DOCREFF,SI_LINENO FROM SI_TBL WHERE SI_DOC=@scanSI GROUP BY SI_DOC,SI_DOCREFF,SI_LINENO
END

ELSE IF (@status = 'getcountitemSISCN')
BEGIN
SELECT A.SI_DOC,A.SI_DOCREFF,A.SI_LINENO, SUM(SI_QTY) SI_QTY,Z.SISCN_SERQTY FROM SI_TBL A 
INNER JOIN(SELECT SISCN_DOC,SISCN_DOCREFF,SISCN_LINENO, SUM(SISCN_SERQTY) AS SISCN_SERQTY FROM
SISCN_TBL WHERE SISCN_DOC=@scanSI GROUP BY SISCN_DOC,SISCN_DOCREFF,SISCN_LINENO) AS Z 
ON Z.SISCN_DOC=A.SI_DOC AND Z.SISCN_DOCREFF=A.SI_DOCREFF AND Z.SISCN_LINENO=A.SI_LINENO
WHERE A.SI_DOC=@scanSI GROUP BY A.SI_DOC,A.SI_DOCREFF,A.SI_LINENO,Z.SISCN_SERQTY
HAVING SUM(A.SI_QTY)<=Z.SISCN_SERQTY
END

ELSE IF (@status = 'getpllt')
BEGIN
SELECT SISCN_PLLT FROM SISCN_TBL WHERE SISCN_DOCREFF=@siscndocreff AND SISCN_DOC=@scanSI AND SISCN_LINENO=@silineno
END
ELSE IF (@status = 'updatepllt')
BEGIN

--DT parameter alias dari serial
UPDATE SISCN_TBL SET  SISCN_PLLT=1 WHERE SISCN_DOCREFF=@siscndocreff AND SISCN_DOC=@scanSI AND SISCN_LINENO=@silineno
AND SISCN_SER=@dt
END
ELSE IF (@status = 'updatepllt2')
BEGIN

--DT parameter alias dari serial
UPDATE SISCN_TBL SET  SISCN_PLLT=2 WHERE SISCN_DOCREFF=@siscndocreff AND SISCN_DOC=@scanSI AND SISCN_LINENO=@silineno
AND SISCN_SER=@dt
END
End


SET ANSI_NULLS ON
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_get_spl_allPSN]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sato_sp_get_spl_allPSN]
@spl_doc varchar(50),
@spl_cat varchar(50),
@spl_lineno varchar(50),
@spl_fedr varchar(50),
@spl_orderno varchar(50),
@spl_itmcd varchar(50),
@spl_rackno varchar(50),
@status varchar(50)
AS
BEGIN
	SET NOCOUNT ON;
	IF (@status ='orderno')

BEGIN

SELECT * FROM spl_tbl 
WHERE spl_doc=@spl_doc AND SPL_CAT=SPL_CAT
AND SPL_LINE=@spl_lineno AND SPL_FEDR=@spl_fedr
AND SPL_ORDERNO=@spl_orderno
END 

ELSE IF (@status = 'rackno')
BEGIN
SELECT * FROM spl_tbl 
WHERE spl_doc=@spl_doc AND SPL_CAT=@spl_cat
AND SPL_LINE=@spl_lineno AND SPL_FEDR=@spl_fedr
AND SPL_ORDERNO=@spl_orderno AND SPL_RACKNO=@spl_rackno
END

ELSE IF(@status ='itmcd')
BEGIN
SELECT * FROM spl_tbl 
WHERE spl_doc=@spl_doc AND SPL_CAT=@spl_cat
AND SPL_LINE=@spl_lineno AND SPL_FEDR=@spl_fedr
AND SPL_ORDERNO=@spl_orderno AND SPL_RACKNO=@spl_rackno and SPL_ITMCD=@spl_itmcd
END
END



GO
/****** Object:  StoredProcedure [dbo].[sato_sp_get_sum_psn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[sato_sp_get_sum_psn]
(
@ScanPSN   [varchar](50),
@splscn_cat [varchar](50),
@splscn_line [varchar](50),
@splscn_fedr [varchar](50),
@scanItem  [varchar](50),
@OrderNo  [varchar](50),
@status   [varchar](30)
)

AS
BEGIN
	SET NOCOUNT ON;

IF (@status = 'spl')
BEGIN
SELECT SUM(SPL_QTYREQ) FROM SPL_TBL WHERE SPL_DOC=@ScanPSN AND SPL_ITMCD=@scanItem AND SPL_ORDERNO=@OrderNo and SPL_CAT=@splscn_cat AND SPL_FEDR=@splscn_fedr AND SPL_LINE=@splscn_line
END
ELSE IF (@status = 'ith')
BEGIN
SELECT SUM(SPLSCN_QTY) FROM SPLSCN_TBL WHERE SPLSCN_DOC=@ScanPSN AND SPLSCN_ITMCD=@scanItem AND SPLSCN_ORDERNO=@OrderNo and SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND SPLSCN_SAVED='1'
END
ELSE IF(@status = 'splscn')
BEGIN
SELECT SUM(SPLSCN_QTY) FROM SPLSCN_TBL WHERE SPLSCN_DOC=@ScanPSN AND SPLSCN_ITMCD=@scanItem AND SPLSCN_ORDERNO=@OrderNo AND SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND SPLSCN_SAVED IS NULL
END
ELSE IF(@status = 'cekith')
BEGIN
SELECT * FROM ITH_TBL WHERE ITH_ITMCD=@scanItem AND ITH_DATE=CAST(getdate() as date) AND ITH_DOC LIKE ''+@ScanPSN +'%' AND ITH_FORM='INC-PRD-RM'
END
else if (@status='updsplscn')
BEGIN
UPDATE SPLSCN_TBL SET SPLSCN_SAVED='1' WHERE SPLSCN_DOC=@ScanPSN  AND SPLSCN_ITMCD=@scanItem AND SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND SPLSCN_ORDERNO=@OrderNo 
END

else if (@status='checkcancel')
BEGIN
SELECT * FROM SPLSCN_TBL WHERE SPLSCN_DOC=@ScanPSN  AND SPLSCN_ITMCD=@scanItem AND SPLSCN_ORDERNO=@OrderNo 
AND SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND SPLSCN_SAVED IS NULL
END

else if (@status='deletecancel')
BEGIN
DELETE FROM SPLSCN_TBL WHERE SPLSCN_DOC=@ScanPSN  AND SPLSCN_ITMCD=@scanItem AND SPLSCN_ORDERNO=@OrderNo AND SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND SPLSCN_SAVED IS NULL

END

else if (@status='countitm')
BEGIN
SELECT A.SPLSCN_DOC,A.SPLSCN_ORDERNO,A.SPLSCN_ITMCD,SUM(A.SPLSCN_QTY) AS SPL_QTY , Z.SPL_QTYREQ FROM SPLSCN_TBL A
INNER JOIN (SELECT SPL_DOC,SPL_ORDERNO,SPL_ITMCD,SUM(SPL_QTYREQ) AS SPL_QTYREQ FROM SPL_TBL 
WHERE SPL_DOC=@ScanPSN AND SPL_CAT=@splscn_cat AND SPL_FEDR=@splscn_fedr AND SPL_LINE=@splscn_line
GROUP BY SPL_DOC,SPL_ORDERNO,SPL_ITMCD) AS Z ON  Z.SPL_DOC=a.SPLSCN_DOC AND Z.SPL_ORDERNO=a.SPLSCN_ORDERNO 
AND Z.SPL_ITMCD=a.SPLSCN_ITMCD 
where  A.SPLSCN_DOC=@ScanPSN and SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND A.SPLSCN_SAVED='1'
GROUP BY A.SPLSCN_DOC,A.SPLSCN_ORDERNO,A.SPLSCN_ITMCD,Z.SPL_QTYREQ
having sum(A.SPLSCN_QTY)>=Z.SPL_QTYREQ
END 
else if (@status='CountItmSPL')
BEGIN
SELECT SPL_DOC FROM SPL_TBL WHERE SPL_DOC=@ScanPSN AND SPL_CAT=@splscn_cat AND SPL_FEDR=@splscn_fedr AND SPL_LINE=@splscn_line  GROUP BY SPL_DOC,SPL_ORDERNO,SPL_ITMCD
END 
END




GO
/****** Object:  StoredProcedure [dbo].[sato_sp_get_user_role]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE PROCEDURE [dbo].[sato_sp_get_user_role]
	@UserID 	[varchar](100)
	
AS
BEGIN
	SET NOCOUNT ON;

select c.UserId,b.role_id,b.rore_name,c.Name,c.Authority FROM sato_user_role a 
inner join sato_mst_role_user b ON a.role_id=b.role_id
INNER JOIN User_ht c ON a.UserId=c.UserId
WHERE a.UserId=@UserID GROUP BY c.UserId,b.role_id,b.rore_name,c.Name,c.Authority


END




GO
/****** Object:  StoredProcedure [dbo].[sato_sp_GetTotalBoxOutFG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[sato_sp_GetTotalBoxOutFG]
	@si_doc 	[varchar](50),
	@si_docreff [varchar](50),
	@si_lineno 	[varchar](50),
	@status     varchar(50)
AS
BEGIN
	SET NOCOUNT ON;

	if (@status ='qty')
	BEGIN
SELECT sum(siscn_serqty) FROM siscn_tbl WHERE siscn_doc=@si_doc AND siscn_docreff=@si_docreff AND
 siscn_lineno=@si_lineno 
 END
 ELSE IF (@status = 'item')
 BEGIN
 SELECT count(*) FROM siscn_tbl WHERE siscn_doc=@si_doc AND siscn_docreff=@si_docreff AND
 siscn_lineno=@si_lineno 
 END

END




GO
/****** Object:  StoredProcedure [dbo].[sato_sp_insert_user_ht]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE PROCEDURE [dbo].[sato_sp_insert_user_ht]
       @userid                      VARCHAR(10)  = NULL   , 
       @passwd                      VARCHAR(8)  = NULL   , 
       @name                        VARCHAR(20)  = NULL   , 
       @Authority                   VARCHAR(12)  = NULL  
	AS
BEGIN 
     SET NOCOUNT ON 

     INSERT INTO User_ht
          (                    
            UserId,
	        Passwd,
	        Name,
	        Authority
	       
		  )
		  values
		  (
		    @userid, 	
            @passwd, 	
			@name,  	
			@Authority
			
			)
END 












GO
/****** Object:  StoredProcedure [dbo].[sato_sp_INSERTSCANRCV]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[sato_sp_INSERTSCANRCV]
		(@RCVSCN_ID [varchar](20), 
		@RCVSCN_DONO [varchar](100), 
		@RCVSCN_LOCID [varchar](50),
		@RCVSCN_ITMCD [varchar](50),
		@RCVSCN_LOTNO [varchar](50),
		@RCVSCN_QTY [decimal](12, 3),
		@RCVSCN_LUPDT [datetime],
		@RCVSCN_USRID [varchar](50),
		@RCVSCN_STATUS [varchar](2))

		AS
BEGIN 
     SET NOCOUNT ON 

INSERT INTO RCVSCN_TBL (RCVSCN_ID,RCVSCN_DONO,RCVSCN_LOCID,RCVSCN_ITMCD,RCVSCN_LOTNO,RCVSCN_QTY,RCVSCN_LUPDT,RCVSCN_USRID,[RCVSCN_SAVED])
VALUES((select [dbo].sato_fun_get_id('RcvRM')),@RCVSCN_DONO,@RCVSCN_LOCID,@RCVSCN_ITMCD,@RCVSCN_LOTNO,@RCVSCN_QTY,getdate(),@RCVSCN_USRID,@RCVSCN_STATUS)
END





GO
/****** Object:  StoredProcedure [dbo].[sato_sp_out_fg_inserttosiscn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[sato_sp_out_fg_inserttosiscn]
		(
		@SISCN_DOC [varchar](50),
@SISCN_DOCREFF [varchar](50),
@SISCN_SER [varchar](50),
@SISCN_SERQTY [decimal](12,3),
@SISCN_LINENO [varchar](50),
@SISCN_ISFIFO [varchar](50),
@SISCN_USRID [varchar](50)


		
		)

		AS
BEGIN 
     SET NOCOUNT ON 

INSERT INTO siscn_tbl(
SISCN_DOC,
SISCN_DOCREFF,
SISCN_SER,
SISCN_SERQTY,
SISCN_LINENO,
SISCN_ISFIFO,
SISCN_LUPDT,
SISCN_USRID
)VALUES(
@SISCN_DOC,
@SISCN_DOCREFF,
@SISCN_SER,
@SISCN_SERQTY,
@SISCN_LINENO,
@SISCN_ISFIFO,
GETDATE(),
@SISCN_USRID
)

END



GO
/****** Object:  StoredProcedure [dbo].[sato_sp_outfg_checksumith]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create PROCEDURE [dbo].[sato_sp_outfg_checksumith]
@serial VARCHAR(50)
AS
BEGIN
SET NOCOUNT ON;
SELECT ISNULL(SUM(ITH_QTY),0) AS ITH_QTY FROM ith_tbl where ith_ser=@serial AND ITH_WH='ARSHP'
END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_pnd_rm_savetoscn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sato_sp_pnd_rm_savetoscn]
(
@PNDSCN_DOC varchar(50),
@PNDSCN_ITMCD varchar(50), 
@PNDSCN_LOTNO varchar(50),
@PNDSCN_QTY decimal(10,3),
@PNDSCN_SER varchar(50),
@PNDSCN_SAVED varchar(20),
@PNDSCN_USRID varchar(50))
AS
BEGIN
SET NOCOUNT ON

INSERT INTO PNDSCN_TBL
VALUES((select [dbo].[sato_fun_get_id]('PendingRM') ),@PNDSCN_DOC,@PNDSCN_ITMCD,
@PNDSCN_LOTNO,@PNDSCN_QTY,@PNDSCN_SER,@PNDSCN_SAVED,GETDATE(),@PNDSCN_USRID)

END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_PSN_CheckActualQTY]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[sato_sp_PSN_CheckActualQTY]
	@PSN	[varchar](100),
	@splscn_cat [varchar](50),
	@splscn_line [varchar](50),
	@splscn_fidr [varchar](50),
	@ITEM 	[varchar](50),
	@ORDERNO [VARCHAR](50)

AS
BEGIN
	SET NOCOUNT ON;

select SUM(SPLSCN_QTY) AS SPLSCN_QTY from SPLSCN_TBL WHERE
 SPLSCN_DOC=@PSN AND SPLSCN_ITMCD=@ITEM and SPLSCN_DOC=@PSN AND SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fidr AND SPLSCN_LINE=@splscn_line AND SPLSCN_ORDERNO=@ORDERNO 
 GROUP BY SPLSCN_ITMCD

END



GO
/****** Object:  StoredProcedure [dbo].[sato_sp_PSN_CheckBeforeSaveToITH]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[sato_sp_PSN_CheckBeforeSaveToITH]
	@PSN	[varchar](100),
	@splscn_cat [varchar](50),
	@splscn_line [varchar](50),
	@splscn_fedr [varchar](50),
	@ITEM 	[varchar](50),
	@ORDERNO [VARCHAR](50)
AS
BEGIN
	SET NOCOUNT ON;

SELECT SPLSCN_ITMCD,MAX(SPLSCN_DOC),SUM(SPLSCN_QTY),MAX(FORMAT(SPLSCN_LUPDT, 'yyyy-MM-dd HH:mm:ss')),MAX(SPLSCN_USRID) 
FROM SPLSCN_TBL WHERE SPLSCN_DOC=@PSN AND SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND SPLSCN_ORDERNO=@ORDERNO 
AND SPLSCN_ITMCD=@ITEM AND SPLSCN_SAVED IS NULL GROUP BY SPLSCN_ITMCD


END




GO
/****** Object:  StoredProcedure [dbo].[sato_sp_PSN_GetItemCodePSN_Detail]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[sato_sp_PSN_GetItemCodePSN_Detail]
	@PSN	[varchar](100),
	@ITEM 	[varchar](50)

AS
BEGIN
	SET NOCOUNT ON;

SELECT SPL_ITMCD,SUM(SPL_QTYREQ) AS SPL_QTYREQ , MAX(SPL_ORDERNO) AS SPL_ORDERNO FROM [dbo].[SPL_TBL] WHERE SPL_DOC=@PSN AND SPL_ITMCD=@ITEM
GROUP BY SPL_ITMCD

END





GO
/****** Object:  StoredProcedure [dbo].[sato_sp_PSN_INSERTSCANSPL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[sato_sp_PSN_INSERTSCANSPL]
		(
		@SPLSCN_ID    [varchar](100),
@SPLSCN_DOC    [varchar](100),
@SPLSCN_CAT    [varchar](100),
@SPLSCN_LINE    [varchar](100),
@SPLSCN_FEDR    [varchar](100),
@SPLSCN_ORDERNO    [varchar](100),
@SPLSCN_ITMCD    [varchar](100),
@SPLSCN_LOTNO    [varchar](100),
@SPLSCN_QTY    [decimal](12,3),
@SPLSCN_SAVED    [varchar](100),
@SPLSCN_LUPDT    [varchar](100),
@SPLSCN_USRID    [varchar](20)

		
		)

		AS
BEGIN 
     SET NOCOUNT ON 

INSERT INTO [SPLSCN_TBL]
(
SPLSCN_ID,
SPLSCN_DOC,
SPLSCN_CAT,
SPLSCN_LINE,
SPLSCN_FEDR,
SPLSCN_ORDERNO,
SPLSCN_ITMCD,
SPLSCN_LOTNO,
SPLSCN_QTY,
SPLSCN_SAVED,
SPLSCN_LUPDT,
SPLSCN_USRID 
)
VALUES
(
( select [dbo].sato_fun_get_id('PSNRM')),
@SPLSCN_DOC,
@SPLSCN_CAT,
@SPLSCN_LINE,
@SPLSCN_FEDR,
@SPLSCN_ORDERNO,
@SPLSCN_ITMCD,
@SPLSCN_LOTNO,
@SPLSCN_QTY,
@SPLSCN_SAVED,
GETDATE(),
@SPLSCN_USRID 

)
END


/****** Object:  UserDefinedFunction [dbo].[sato_fun_get_id]    Script Date: 2020-05-06 16:42:55 ******/
SET ANSI_NULLS ON
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SaveToITH]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sato_sp_SaveToITH]
(
@ITH_ITMCD [varchar](50),
@ITH_DATE [DATETIME],
@ITH_FORM [varchar](50),
@ITH_DOC [varchar](50),
@ITH_QTY [decimal](12, 3),
@ITH_WH [varchar](50),
@ITH_SER [varchar](50),
@ITH_REMARK [varchar](50),
@ITH_LOC [varchar](50),
@ITH_LUPDT [varchar](50),
@ITH_USRID [varchar](50),
@status   [varchar](50)
)

		AS
BEGIN 
     SET NOCOUNT ON 
--SATO HERE
--IF (@status ='Insert')
--BEGIN
--INSERT INTO [ITH_TBL] 
--(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LINE,ITH_LOC,ITH_LUPDT,ITH_USRID)
--VALUES(@ITH_ITMCD,CAST(getdate() AS date),@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK,
--([dbo].[fun_ithline] ()),@ITH_LOC,@ITH_LUPDT,@ITH_USRID)
--END

--ELSE IF(@status='Update')
--BEGIN
--UPDATE ITH_TBL SET ITH_QTY=ITH_QTY+@ITH_QTY,ITH_LUPDT=@ITH_LUPDT WHERE ITH_ITMCD=@ITH_ITMCD AND ITH_DATE=CAST(getdate() as date)
--AND ITH_DOC=@ITH_DOC AND ITH_FORM=@ITH_FORM
--END
--SATO END HERE
DECLARE @BG VARCHAR(50)
DECLARE @BOOKID VARCHAR(50)
IF (@status ='Insert')
	BEGIN
	if (@ITH_FORM='OUT-WH-FG')
		BEGIN
		INSERT INTO [ITH_TBL] 
		(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LOC,ITH_LUPDT,ITH_USRID)
		VALUES(@ITH_ITMCD,CAST(getdate() AS date),@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK,dbo.fun_delv_location_be4(@ITH_WH,@ITH_SER),@ITH_LUPDT,@ITH_USRID)
		END
	else if (@ITH_FORM='INC-DO')
		BEGIN
		DECLARE @TGLBC date
		set @TGLBC = (SELECT max(RCV_BCDATE) RCV_BCDATE FROM RCV_TBL WHERE RCV_DONO=@ITH_DOC)
		--2021-12-10
		--x===========PERUBAHAN ATURAN========
		--Tidak perlu menyimpan ket ITH_TBL karena INC-DO akan dilakukan ketika EXIM melakukan update
		--===================================
		--INSERT INTO [ITH_TBL] 
		--(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LINE,ITH_LOC,ITH_LUPDT,ITH_USRID)
		--VALUES(@ITH_ITMCD,@TGLBC,@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK,
		--([dbo].[fun_ithline] ()),dbo.fun_delv_location_be4(@ITH_WH,@ITH_SER),CONCAT(@TGLBC,' ','07:01:01'),@ITH_USRID)
		END
	else if (@ITH_FORM='OUT-WH-RM' AND SUBSTRING(@ITH_DOC,1,3) != 'PND')
		BEGIN
	
			SET @BG = (SELECT TOP 1 RTRIM(SPL_BG) FROM SPL_TBL WHERE SPL_DOC=SUBSTRING(@ITH_DOC,1,19))
			SET @ITH_WH = CASE 
				WHEN @BG='PSI1PPZIEP' THEN 'ARWH1'
				WHEN @BG='PSI2PPZADI' THEN 'ARWH2'
				WHEN @BG='PSI2PPZSTY' THEN 'ARWH2'
				WHEN @BG='PSI2PPZOMI' THEN 'ARWH2'
				WHEN @BG='PSI2PPZTDI' THEN 'ARWH2'
				WHEN @BG='PSI2PPZINS' THEN 'NRWH2'
				WHEN @BG='PSI2PPZOMC' THEN 'NRWH2'			
				WHEN @BG='PSI2PPZSSI' THEN 'NRWH2'			
				END		
		INSERT INTO [ITH_TBL] 
		(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LOC,ITH_LUPDT,ITH_USRID)
		VALUES(@ITH_ITMCD,@ITH_LUPDT,@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK
		,@ITH_LOC,@ITH_LUPDT,@ITH_USRID)
		END
	else if (@ITH_FORM='INC-PRD-RM')
		BEGIN	
			SET @BG = (SELECT TOP 1 RTRIM(SPL_BG) FROM SPL_TBL WHERE SPL_DOC=SUBSTRING(@ITH_DOC,1,19))		
			SET @ITH_WH = CASE 
				WHEN @BG='PSI1PPZIEP' THEN 'PLANT1'
				WHEN @BG='PSI2PPZADI' THEN 'PLANT2'
				WHEN @BG='PSI2PPZSTY' THEN 'PLANT2'
				WHEN @BG='PSI2PPZOMI' THEN 'PLANT2'
				WHEN @BG='PSI2PPZTDI' THEN 'PLANT2'
				WHEN @BG='PSI2PPZINS' THEN 'PLANT_NA'
				WHEN @BG='PSI2PPZOMC' THEN 'PLANT_NA'			
				WHEN @BG='PSI2PPZSSI' THEN 'PLANT_NA'			
				END
		INSERT INTO [ITH_TBL] 
		(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LOC,ITH_LUPDT,ITH_USRID)
		VALUES(@ITH_ITMCD,@ITH_LUPDT,@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK,
		@ITH_LOC,@ITH_LUPDT,@ITH_USRID)
		END
	ELSE
		BEGIN
		INSERT INTO [ITH_TBL] 
		(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LINE,ITH_LOC,ITH_LUPDT,ITH_USRID)
		VALUES(@ITH_ITMCD,CAST(getdate() AS date),@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK,
		([dbo].[fun_ithline] ()),@ITH_LOC,GETDATE(),@ITH_USRID)
		END
	END


ELSE IF(@status='Update')
BEGIN
UPDATE ITH_TBL SET ITH_QTY=ITH_QTY+@ITH_QTY WHERE ITH_ITMCD=@ITH_ITMCD AND ITH_DATE=CAST(getdate() as date)
AND ITH_DOC=@ITH_DOC AND ITH_FORM=@ITH_FORM
END


END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SaveToITHFG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sato_sp_SaveToITHFG]
(
		@ITH_ITMCD [varchar](50), 
        @ITH_DATE [DATETIME] ,

        @ITH_FORMIN [varchar](50),
        @ITH_FORMOUT [varchar](50),

        @ITH_DOC [varchar](50),

        @ITH_QTYIN [decimal](12, 3),
        @ITH_QTYOUT [decimal](12, 3),

        @ITH_WHIN [varchar](50),
        @ITH_WHOUT [varchar](50),

        @ITH_SER [varchar](50),
        @ITH_REMARK [varchar](50),
        @ITH_LOC [varchar](50),
        @ITH_LUPDT [varchar](50),
        @ITH_USRID [varchar](50)


)

		AS
BEGIN 
     SET NOCOUNT ON 

	 --OUT QA
INSERT INTO [ITH_TBL] 
(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LOC,ITH_LUPDT,ITH_USRID)
VALUES(@ITH_ITMCD,CAST(getdate() AS date),@ITH_FORMOUT,@ITH_DOC,@ITH_QTYOUT,@ITH_WHOUT,@ITH_SER,@ITH_REMARK,
@ITH_LOC,@ITH_LUPDT,@ITH_USRID)


	--IN FG
INSERT INTO [ITH_TBL] 
(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LOC,ITH_LUPDT,ITH_USRID)
VALUES(@ITH_ITMCD,CAST(getdate() AS date),@ITH_FORMIN,@ITH_DOC,@ITH_QTYIN,@ITH_WHIN,@ITH_SER,@ITH_REMARK,
@ITH_LOC,@ITH_LUPDT,@ITH_USRID)

END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SaveToITHTRFFG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sato_sp_SaveToITHTRFFG]
(
		@ITH_ITMCD [varchar](50), 
        @ITH_DATE [DATETIME] ,

        @ITH_FORMIN [varchar](50),
        @ITH_FORMOUT [varchar](50),

        @ITH_DOC [varchar](50),

        @ITH_QTYIN [decimal](12, 3),
        @ITH_QTYOUT [decimal](12, 3),

        @ITH_WHIN [varchar](50),
        @ITH_WHOUT [varchar](50),

        @ITH_SER [varchar](50),
        @ITH_REMARK [varchar](50),
        @ITH_LOCOUT [varchar](50),
		@ITH_LOCIN [varchar](50),
        @ITH_LUPDT [varchar](50),
        @ITH_USRID [varchar](50)


)

		AS
BEGIN 
     SET NOCOUNT ON 

	 --OUT QA
INSERT INTO [ITH_TBL] 
(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LOC,ITH_LUPDT,ITH_USRID)
VALUES(@ITH_ITMCD,CAST(getdate() AS date),@ITH_FORMOUT,@ITH_DOC,@ITH_QTYOUT,@ITH_WHOUT,@ITH_SER,@ITH_REMARK,
@ITH_LOCOUT,@ITH_LUPDT,@ITH_USRID)


	--IN FG
INSERT INTO [ITH_TBL] 
(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LOC,ITH_LUPDT,ITH_USRID)
VALUES(@ITH_ITMCD,CAST(getdate() AS date),@ITH_FORMIN,@ITH_DOC,@ITH_QTYIN,@ITH_WHIN,@ITH_SER,@ITH_REMARK,
@ITH_LOCIN,@ITH_LUPDT,@ITH_USRID)

END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SCR_rm_savetoscn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sato_sp_SCR_rm_savetoscn]
(
@SCRSCN_DOC varchar(50),
@SCRSCN_ITMCD varchar(50), 
@SCRSCN_LOTNO varchar(50),
@SCRSCN_QTY decimal(7,3),
@SCRSCN_SER varchar(50),
@SCRSCN_SAVED varchar(20),
@SCRSCN_USRID varchar(50))
AS
BEGIN
SET NOCOUNT ON

INSERT INTO SCRSCN_TBL
VALUES((select [dbo].[sato_fun_get_id]('ScrapRM') ),@SCRSCN_DOC,@SCRSCN_ITMCD,
@SCRSCN_LOTNO,@SCRSCN_QTY,@SCRSCN_SER,@SCRSCN_SAVED,GETDATE(),@SCRSCN_USRID)

END 



GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SELECT_ITEM]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[sato_sp_SELECT_ITEM]
(
	@Donoscan [VARCHAR](30),
	@item [varchar](20),
	@loc  [varchar](20)
)

AS
BEGIN
	SET NOCOUNT ON;

IF (@loc <> '')

BEGIN
SELECT a.[RCV_ITMCD],a.[RCV_DONO],sum(a.[RCV_QTY]) AS RCV_QTY,b.[ITMLOC_LOC],b.[ITMLOC_USRID]
 FROM [dbo].[RCV_TBL] a
   inner join [dbo].[ITMLOC_TBL] b ON (a.[RCV_ITMCD] = b.[ITMLOC_ITM])
  
WHERE
(
	a.[RCV_DONO]	=	@Donoscan AND  b.[ITMLOC_LOC]= @loc AND a.[RCV_ITMCD]= @item

)
GROUP BY a.[RCV_ITMCD],a.[RCV_DONO],b.[ITMLOC_LOC],b.[ITMLOC_USRID]

END


ELSE IF (@loc ='')

BEGIN

SELECT a.[RCV_ITMCD],a.[RCV_DONO],SUM(a.[RCV_QTY]) AS [RCV_QTY],b.[ITMLOC_LOC],b.[ITMLOC_USRID]
 FROM [dbo].[RCV_TBL] a
   inner join [dbo].[ITMLOC_TBL] b ON (a.[RCV_ITMCD] = b.[ITMLOC_ITM])
  
WHERE
(
	a.[RCV_DONO] = @Donoscan  AND a.[RCV_ITMCD]= @item

)
GROUP BY a.[RCV_ITMCD],a.[RCV_DONO],b.[ITMLOC_LOC],b.[ITMLOC_USRID]
END
END





GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SELECTCOUNTITM]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sato_sp_SELECTCOUNTITM]
	@Donoscan 	[varchar](100)
AS
BEGIN
	SET NOCOUNT ON;

	---only scan lot


SELECT a.RCVSCN_DONO ITH_DOC,a.RCVSCN_ITMCD ITH_ITMCD,SUM(A.RCVSCN_QTY) as ITH_QTY,z.Total as RCV_QTY
from RCVSCN_TBL a inner join
(SELECT RCV_DONO,RCV_ITMCD,SUM(RCV_QTY) as Total
from RCV_TBL
where RCV_DONO =@Donoscan group by RCV_DONO,RCV_ITMCD)
 z on a.RCVSCN_DONO=z.RCV_DONO and a.RCVSCN_ITMCD=z.RCV_ITMCD
where a.RCVSCN_DONO =@Donoscan group by a.RCVSCN_DONO,a.RCVSCN_ITMCD,z.Total
having SUM(A.RCVSCN_QTY)>=z.Total


-- SELECT a.ITH_DOC,a.ITH_ITMCD,SUM(A.ITH_QTY) as ITH_QTY,z.Total as RCV_QTY
--from ITH_TBL a inner join
--(SELECT RCV_DONO,RCV_ITMCD,SUM(RCV_QTY) as Total
--from RCV_TBL
--where RCV_DONO =@Donoscan group by RCV_DONO,RCV_ITMCD)
-- z on a.ITH_DOC=z.RCV_DONO and a.ITH_ITMCD=z.RCV_ITMCD
--where a.ITH_DOC =@Donoscan group by a.ITH_DOC,a.ITH_ITMCD,z.Total
--having SUM(A.ITH_QTY)>=z.Total

END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SELECTDO]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[sato_sp_SELECTDO]
	@Donoscan 	[varchar](100)
AS
BEGIN
	SET NOCOUNT ON;

Select 		RCV_DONO	AS	'RCV_DONO'
		
From	RCV_TBL
WHERE
(
	RCV_DONO	=	@Donoscan 
)
END






GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SELECTDOall]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[sato_sp_SELECTDOall]
	@Donoscan 	[varchar](100),
	@item		[varchar](50)
AS
BEGIN
	SET NOCOUNT ON;




--SELECT [RCVSCN_ITMCD],[RCVSCN_DONO],0 as [RCV_QTY],sum([RCVSCN_QTY]) as [RCVSCN_QTY],RCVSCN_LOCID,
--RCVSCN_USRID,RCVSCN_SAVED,(select [dbo].[sato_wh_rcvtbl](@Donoscan,@item) )  AS WH
-- FROM [dbo].[RCVSCN_TBL]
  
  
--WHERE
--(
--	[RCVSCN_DONO]	=	@Donoscan  AND RCVSCN_ITMCD=@item and RCVSCN_SAVED IS NULL

--)
--GROUP BY [RCVSCN_ITMCD],[RCVSCN_DONO],RCVSCN_LOCID,RCVSCN_USRID,[RCVSCN_SAVED];

--- 24-05-2021
SELECT [RCVSCN_ITMCD],[RCVSCN_DONO],0 as [RCV_QTY],sum([RCVSCN_QTY]) as [RCVSCN_QTY],RCVSCN_LOCID,
MAX(RCVSCN_USRID) as RCVSCN_USRID ,RCVSCN_SAVED,(select [dbo].[sato_wh_rcvtbl](@Donoscan,@item) )  AS WH
 FROM [dbo].[RCVSCN_TBL]
  
  
WHERE
(
	[RCVSCN_DONO]	=	@Donoscan  AND RCVSCN_ITMCD=@item and RCVSCN_SAVED IS NULL

)
GROUP BY [RCVSCN_ITMCD],[RCVSCN_DONO],RCVSCN_LOCID,[RCVSCN_SAVED];
END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SELECTDOSCAN]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[sato_sp_SELECTDOSCAN]
	@Donoscan 	[varchar](100)
AS
BEGIN
	SET NOCOUNT ON;

Select 		RCVSCN_DONO	AS	'RCVSCN_DONO'
		
		
From	RCVSCN_TBL
WHERE
(
	RCVSCN_DONO	=	@Donoscan
)
END






GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SELECTFEDR]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[sato_sp_SELECTFEDR]
(
@SPL_DOC	[varchar](50)	,
@SPL_CAT	[varchar](50),
@SPL_LINE	[varchar](50),
@SPL_FEDR	[varchar](50)
)
AS
BEGIN
	SET NOCOUNT ON;

SELECT * FROM SPL_TBL WHERE SPL_FEDR=@SPL_FEDR
AND SPL_LINE=@SPL_LINE
AND SPL_CAT=@SPL_CAT
AND SPL_DOC=@SPL_DOC
END







GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SELECTiTEMNLOC]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[sato_sp_SELECTiTEMNLOC]
	@LOCNO 	[varchar](50)
AS
BEGIN
	SET NOCOUNT ON;

Select 		ITMLOC_LOC	AS	'ITMLOC_LOC',
			ITMLOC_ITM AS 'ITMLOC_ITM'
		
From	ITMLOC_TBL
WHERE 
(
	ITMLOC_LOC	=	@LOCNO
)
END






GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SELECTLOC]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[sato_sp_SELECTLOC]
	@DONO   [varchar](100),
	@LOCNO 	[varchar](50)
AS
BEGIN
	SET NOCOUNT ON;

SELECT ITMLOC_ITM,ITMLOC_LOC ,SUM(RCV_QTY) AS RCV_QTY FROM ITMLOC_TBL a 
inner join RCV_TBL b on (a.ITMLOC_ITM=b.RCV_ITMCD)

WHERE a.ITMLOC_LOC=@LOCNO AND b.RCV_DONO=@DONO group by ITMLOC_ITM,ITMLOC_LOC
END


GO
/****** Object:  StoredProcedure [dbo].[sato_sp_SPAll]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[sato_sp_SPAll]   
@statement NVARCHAR(4000)
AS
BEGIN
	EXECUTE sp_executesql @statement
END;




GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_adjFG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sato_sp_spall_adjFG]
@ser VARCHAR(50),
@wh  varchar(50),
@status VARCHAR(50)
AS
BEGIN
	SET NOCOUNT ON;


IF (@status ='checkitem')
BEGIN

SELECT * FROM SER_TBL WHERE SER_ID=@ser

END 
IF (@status ='getStockQty')
BEGIN

SELECT ISNULL(SUM(ITH_QTY),0),MAX(ITH_ITMCD) FROM ITH_TBL WHERE ITH_SER=@ser AND ITH_WH=@wh

END 

IF (@status ='checkWH')
BEGIN
select * from MSTLOC_TBL a , MSTLOCG_TBL b  where a.MSTLOC_GRP=b.MSTLOCG_ID AND a.MSTLOC_GRP=@wh;
END
END

GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_adjRM]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sato_sp_spall_adjRM]
@itemcode VARCHAR(50),
@wh  varchar(50),
@status VARCHAR(50)
AS
BEGIN
	SET NOCOUNT ON;


IF (@status ='checkitem')
BEGIN

SELECT * FROM MITM_TBL WHERE MITM_ITMCD=@itemcode

END 
IF (@status ='getStockQty')
BEGIN

SELECT ISNULL(SUM(ITH_QTY),0) FROM ITH_TBL WHERE ITH_ITMCD=@itemcode AND ITH_WH=@wh

END 

IF (@status ='checkWH')
BEGIN
select * from MSTLOC_TBL a , MSTLOCG_TBL b  where a.MSTLOC_GRP=b.MSTLOCG_ID AND a.MSTLOC_GRP=@wh;
END
END

GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_deliveryFG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sato_sp_spall_deliveryFG]
@doc varchar(50),
@docreff varchar(50),
@ser varchar(50),
@itmcd varchar(50),
@status varchar(50)
AS
BEGIN
	SET NOCOUNT ON;
IF (@status ='GetSerial')
BEGIN
/* docreff untuk disini jadi WH*/
/* UPDATE tanggal 15/06/2021*/

select MAX(SER_TBL.SER_QTY) AS SER_QTY ,sum(ith_qty) AS ITH_QTY from ITH_TBL 
inner join SER_TBL ON SER_TBL.SER_ID=ITH_TBL.ITH_SER where
ITH_SER=@ser and ITH_WH=@docreff and ITH_ITMCD=@itmcd and ITH_FORM NOT IN ('SASTART','SA') 
GROUP BY ITH_ITMCD,ITH_SER having sum(ITH_QTY)>0;
/* UPDATE tanggal 15/06/2021*/

/*select  sum(ith_qty) AS ITH_QTY from ITH_TBL where
ITH_SER=@ser and ITH_WH=@docreff and ITH_ITMCD=@itmcd and ITH_FORM NOT IN ('SASTART','SA') 
GROUP BY ITH_ITMCD,ITH_SER having sum(ITH_QTY)>0;*/
END 
IF (@status ='CheckScanSI')
BEGIN
SELECT * FROM si_tbl WHERE si_doc=@doc
END 
IF (@status ='CheckSiscanPerSerial')
BEGIN
SELECT * FROM SISCN_TBL WHERE SISCN_SER=@ser AND SISCN_DOC=@doc AND SISCN_DOCREFF=@docreff
END 
IF (@status ='CheckITHperSer')
BEGIN
select * from ITH_TBL where ITH_FORM='INC-SHP-FG' AND ITH_DOC=@doc AND ITH_SER=@ser AND ITH_ITMCD=@itmcd
END
END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_pnd_RM]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sato_sp_spall_pnd_RM]
@doc varchar(50),
@itmcd varchar(50),
@lot varchar(50),
@status varchar(50)
AS
BEGIN
	SET NOCOUNT ON;

IF (@status ='countitem')
BEGIN
select A.PNDSCN_DOC,PNDSCN_ITMCD,SUM(A.PNDSCN_QTY) AS PNDSCN_QTY ,Z.PND_QTY FROM PNDSCN_TBL A
INNER JOIN (SELECT PND_DOC,PND_ITMCD,SUM(PND_QTY) AS PND_QTY FROM  PND_TBL WHERE PND_DOC=@doc GROUP BY 
PND_DOC,PND_ITMCD) AS Z ON A.PNDSCN_DOC=Z.PND_DOC AND A.PNDSCN_ITMCD=Z.PND_ITMCD
WHERE A.PNDSCN_DOC=@doc and A.PNDSCN_SAVED='1'
GROUP BY A.PNDSCN_DOC,PNDSCN_ITMCD ,Z.PND_QTY
HAVING SUM(A.PNDSCN_QTY)>=Z.PND_QTY

END 

ELSE IF (@status ='checkitem')
BEGIN
select a.PND_ITMCD,b.ITMLOC_LOC,a.PND_ITMLOT from PND_TBL a ,
ITMLOC_TBL b  where a.PND_ITMCD=b.ITMLOC_ITM and a.PND_DOC=@doc and a.PND_ITMCD=@itmcd
END

ELSE IF (@status='sumqtyscn')
BEGIN
select A.PNDSCN_DOC,A.PNDSCN_ITMCD,SUM(A.PNDSCN_QTY) AS PNDSCN_QTY ,Z.PND_QTY FROM PNDSCN_TBL A
INNER JOIN (SELECT PND_DOC,PND_ITMCD,SUM(PND_QTY) AS PND_QTY FROM  PND_TBL WHERE PND_DOC=@doc AND 
PND_ITMCD=@itmcd GROUP BY 
PND_DOC,PND_ITMCD) AS Z ON A.PNDSCN_DOC=Z.PND_DOC AND A.PNDSCN_ITMCD=Z.PND_ITMCD
WHERE A.PNDSCN_DOC=@doc AND A.PNDSCN_ITMCD=@itmcd GROUP BY A.PNDSCN_DOC,PNDSCN_ITMCD ,Z.PND_QTY
END

ELSE IF (@status='forITH')
BEGIN
SELECT PNDSCN_ITMCD,PNDSCN_LOTNO,MAX(PNDSCN_DOC),SUM(PNDSCN_QTY),MAX(FORMAT(PNDSCN_LUPDT, 'yyyy-MM-dd HH:mm:ss')),MAX(PNDSCN_USRID) 
FROM PNDSCN_TBL WHERE PNDSCN_DOC=@doc
AND PNDSCN_ITMCD=@itmcd AND PNDSCN_SAVED IS NULL GROUP BY PNDSCN_ITMCD,PNDSCN_LOTNO
END

ELSE IF (@status='UpdatesavedPndScn')
BEGIN

UPDATE PNDSCN_TBL SET PNDSCN_SAVED='1' WHERE PNDSCN_DOC=@doc AND PNDSCN_ITMCD=@itmcd AND PNDSCN_SAVED IS NULL;
END


else if (@status='checkcancel')
BEGIN
SELECT * FROM PNDSCN_TBL WHERE PNDSCN_DOC=@doc  AND PNDSCN_ITMCD=@itmcd AND PNDSCN_SAVED IS NULL
END

else if (@status='deletecancel')
BEGIN
DELETE FROM PNDSCN_TBL WHERE PNDSCN_DOC=@doc  AND PNDSCN_ITMCD=@itmcd  AND PNDSCN_SAVED IS NULL

END
else if (@status='checkFinish')
BEGIN
SELECT a.PNDSCN_DOC , a.PNDSCN_ITMCD , sum(a.pndscn_qty) AS PNDSCN_QTY,z.PND_QTY 
FROM PNDSCN_TBL a inner join 
(SELECT PND_DOC , PND_ITMCD,SUM(PND_QTY) as PND_QTY FROM  PND_TBL WHERE PND_DOC=@doc AND PND_ITMCD=@itmcd
GROUP BY PND_DOC , PND_ITMCD) as z ON a.PNDSCN_DOC=z.PND_DOC and a.PNDSCN_ITMCD=z.PND_ITMCD
WHERE a.PNDSCN_DOC=@doc AND a.PNDSCN_ITMCD=@itmcd AND a.PNDSCN_SAVED is not null
GROUP BY a.pndscn_doc , a.pndscn_itmcd,z.PND_QTY
HAVING sum(a.pndscn_qty)>=z.PND_QTY 
END

ELSE IF(@status = 'checkLot')
BEGIN
--REVISI ADA ERROR TIDAK BISA SCAN PARTIL INFO DARI PAK RIKI TGL 06/08/2021
--
--SELECT * FROM PND_TBL WHERE PND_DOC=@doc AND PND_ITMCD=@itmcd AND PND_ITMLOT=@lot
--AND PND_DOC NOT IN(SELECT PND_DOC FROM PNDSCN_TBL WHERE PNDSCN_DOC=@doc AND PNDSCN_ITMCD=@itmcd 
--AND PNDSCN_LOTNO=@lot)

--REVISI HANYA 1 LOT ANA
--select top 1 PND_DOC,PND.PND_ITMCD,PND.PND_ITMLOT,MAX(PND.PND_QTY) AS PND_QTY, COALESCE(sum(PNDS.PNDSCN_QTY),0) AS PNDSCN_QTY FROM PND_TBL pnd LEFT JOIN 
--PNDSCN_TBL PNDS ON PND.PND_DOC=PNDS.PNDSCN_DOC 
--WHERE  PND_DOC=@doc AND PND_ITMCD=@itmcd AND PND_ITMLOT=@lot
--GROUP BY PND.PND_DOC,PND.PND_ITMCD,PND.PND_ITMLOT
--HAVING MAX(PND.PND_QTY)>COALESCE(SUM(PNDS.PNDSCN_QTY),0)

select  PND_DOC,PND.PND_ITMCD,PND.PND_ITMLOT,SUM(PND.PND_QTY) AS PND_QTY, COALESCE(sum(PNDS.PNDSCN_QTY),0) AS PNDSCN_QTY 
FROM PND_TBL pnd LEFT JOIN 
PNDSCN_TBL PNDS ON PND.PND_DOC=PNDS.PNDSCN_DOC AND PND.PND_ITMCD=PNDS.PNDSCN_ITMCD AND PND_ITMLOT=PNDSCN_LOTNO
WHERE  PND_DOC=@doc AND PND_ITMCD=@itmcd AND PND_ITMLOT=@lot
GROUP BY PND.PND_DOC,PND.PND_ITMCD,PND.PND_ITMLOT
HAVING SUM(PND.PND_QTY)>COALESCE(SUM(PNDS.PNDSCN_QTY),0)


END
ELSE IF (@status = 'CheckDoc')
BEGIN
SELECT PND_DOC FROM PND_TBL WHERE PND_DOC=@doc
END
ELSE IF (@status = 'GetCountItem')
BEGIN
SELECT PND_DOC,PND_ITMCD FROM PND_TBL WHERE PND_DOC=@doc Group By PND_DOC,PND_ITMCD
END
ELSE IF (@status = 'GetsumQtypdbtbl')
BEGIN
SELECT SUM(PND_QTY) FROM PND_TBL WHERE PND_DOC=@doc AND PND_ITMCD=@itmcd;
END
END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_PNDSER_FG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sato_sp_spall_PNDSER_FG]
@doc varchar(50),
@ser varchar(50),
@itmcd varchar(50),
@status varchar(50)
AS
BEGIN
	SET NOCOUNT ON;


IF (@status ='countitem')
BEGIN
select A.PNDSCN_DOC,PNDSCN_ITMCD,SUM(A.PNDSCN_QTY) AS PNDSCN_QTY ,Z.PNDSER_QTY FROM PNDSCN_TBL A
INNER JOIN (SELECT a.PNDSER_DOC ,B.SER_ITMID  , SUM(a.PNDSER_QTY) as PNDSER_QTY from  PNDSER_TBL a , SER_TBL b
 WHERE A.PNDSER_SER=B.SER_ID and a.PNDSER_DOC=@doc
GROUP BY a.PNDSER_DOC ,B.SER_ITMID ) AS Z ON A.PNDSCN_DOC=Z.PNDSER_DOC AND A.PNDSCN_ITMCD=Z.SER_ITMID
WHERE A.PNDSCN_DOC=@doc and A.PNDSCN_SAVED='1' AND PNDSCN_SER IS NOT NULL
GROUP BY A.PNDSCN_DOC,A.PNDSCN_ITMCD ,Z.PNDSER_QTY
HAVING SUM(A.PNDSCN_QTY)>=Z.PNDSER_QTY

END 

ELSE IF (@status ='countItemPndser')
BEGIN
SELECT a.PNDSER_DOC ,B.SER_ITMID FROM PNDSER_TBL a , SER_TBL b WHERE
a.PNDSER_DOC=@doc AND A.PNDSER_SER=B.SER_ID
GROUP BY a.PNDSER_DOC ,B.SER_ITMID
END 

ELSE IF (@status ='checkitem')
BEGIN
select b.SER_DOC,b.SER_ID,b.SER_ITMID,b.SER_QTY ,b.SER_LOTNO from PNDSER_TBL a ,
SER_TBL b  where a.PNDSER_SER=b.SER_ID and a.PNDSER_DOC=@doc and a.PNDSER_SER=@ser
END

else if (@status='sumqtypndser')
BEGIN
SELECT a.PNDSER_DOC ,B.SER_ITMID  ,MAX(B.SER_ITMID), SUM(a.PNDSER_QTY) as PNDSER_QTY from  PNDSER_TBL a , SER_TBL b 
WHERE A.PNDSER_SER=B.SER_ID AND a.PNDSER_DOC=@doc AND b.SER_ITMID=@itmcd
GROUP BY a.PNDSER_DOC ,B.SER_ITMID 
END

ELSE IF (@status='sumqtyscn')
BEGIN
select A.PNDSCN_DOC,A.PNDSCN_ITMCD,SUM(A.PNDSCN_QTY) AS PNDSCN_QTY ,Z.PNDSER_QTY FROM PNDSCN_TBL A
INNER JOIN (SELECT a.PNDSER_DOC ,B.SER_ITMID  , SUM(a.PNDSER_QTY) as PNDSER_QTY from  PNDSER_TBL a , SER_TBL b
 WHERE A.PNDSER_SER=B.SER_ID and a.PNDSER_DOC=@doc AND b.SER_ITMID=@itmcd
GROUP BY a.PNDSER_DOC ,B.SER_ITMID) AS Z ON A.PNDSCN_DOC=Z.PNDSER_DOC AND A.PNDSCN_ITMCD=Z.SER_ITMID
WHERE A.PNDSCN_DOC=@doc AND A.PNDSCN_ITMCD=@itmcd GROUP BY A.PNDSCN_DOC,PNDSCN_ITMCD ,Z.PNDSER_QTY
END

ELSE IF (@status='forITH')
BEGIN
SELECT PNDSCN_ITMCD,PNDSCN_SER,MAX(PNDSCN_DOC),SUM(PNDSCN_QTY),MAX(FORMAT(PNDSCN_LUPDT, 'yyyy-MM-dd HH:mm:ss')),MAX(PNDSCN_USRID) 
FROM PNDSCN_TBL WHERE PNDSCN_DOC=@doc AND PNDSCN_SAVED IS NULL 
GROUP BY PNDSCN_ITMCD,PNDSCN_SER
END

ELSE IF (@status='UpdatesavedPndScn')
BEGIN

UPDATE PNDSCN_TBL SET PNDSCN_SAVED='1' WHERE PNDSCN_DOC=@doc AND PNDSCN_SAVED IS NULL;

END


else if (@status='checkcancel')
BEGIN
SELECT * FROM PNDSCN_TBL WHERE PNDSCN_DOC=@doc  AND PNDSCN_ITMCD=@itmcd AND PNDSCN_SAVED IS NULL
END

else if (@status='deletecancel')
BEGIN
DELETE FROM PNDSCN_TBL WHERE PNDSCN_DOC=@doc  AND PNDSCN_ITMCD=@itmcd  AND PNDSCN_SAVED IS NULL

END
else if (@status='checkFinish')
BEGIN
select A.PNDSCN_DOC,SUM(A.PNDSCN_QTY) AS PNDSCN_QTY ,Z.PNDSER_QTY FROM PNDSCN_TBL A
INNER JOIN (SELECT a.PNDSER_DOC  , SUM(a.PNDSER_QTY) as PNDSER_QTY from  PNDSER_TBL a , SER_TBL b
 WHERE A.PNDSER_SER=B.SER_ID and a.PNDSER_DOC=@doc
GROUP BY a.PNDSER_DOC) AS Z ON A.PNDSCN_DOC=Z.PNDSER_DOC 
WHERE A.PNDSCN_DOC=@doc GROUP BY A.PNDSCN_DOC ,Z.PNDSER_QTY
HAVING SUM(A.PNDSCN_QTY)>=Z.PNDSER_QTY;

END 
else if (@status='GetRack')
BEGIN
SELECT ITMLOC_LOC from  ITMLOC_TBL  WHERE ITMLOC_ITM IN(SELECT SER_ITMID FROM SER_TBL where SER_ID=@ser)
END

ELSE IF(@status = 'checkserialexists')

BEGIN

SELECT * FROM PNDSCN_TBL WHERE PNDSCN_DOC=@doc  AND PNDSCN_SER=@ser;

END

END




GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_rcv_rm]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[sato_sp_spall_rcv_rm]
	@Donoscan 	[varchar](100),
	@loc 	[varchar](50),
	@Item 	[varchar](50),
	@status [varchar](50)
AS
BEGIN
	SET NOCOUNT ON;

IF ( @status = 'checkqtyrcv')
BEGIN
SELECT ISNULL(SUM(RCV_QTY),0) FROM RCV_TBL WHERE RCV_DONO=@Donoscan AND RCV_ITMCD=@Item
END
IF ( @status = 'checkqtyrcvscn')
BEGIN
SELECT ISNULL(SUM(RCVSCN_QTY),0) FROM RCVSCN_TBL WHERE RCVSCN_DONO=@Donoscan AND RCVSCN_ITMCD=@Item
END

IF ( @status = 'GetDataWareHost')
BEGIN
SELECT * FROM [MSTLOCG_TBL] WHERE  [MSTLOCG_ID]=@loc
END

IF ( @status = 'CekQTYjikatidakada')
BEGIN
SELECT SUM(RCV_QTY) FROM rcv_tbl WHERE rcv_dono=@Donoscan AND RCV_ITMCD=@Item
END
IF ( @status = 'CekQTYalreadyITH')
BEGIN

SELECT SUM(ITH_QTY) FROM ITH_TBL WHERE ITH_DOC=@Donoscan AND ITH_ITMCD=@Item GROUP BY ITH_ITMCD
END
IF ( @status = 'cekITH')
BEGIN
SELECT * FROM ITH_TBL WHERE ITH_ITMCD=@Item AND ITH_DATE=CAST(getdate() as date) AND ITH_DOC=@Donoscan
END


IF ( @status = 'SaveToITH')
BEGIN
UPDATE RCVSCN_TBL SET RCVSCN_SAVED='1' WHERE RCVSCN_DONO=@Donoscan AND RCVSCN_ITMCD=@Item
END
IF ( @status = 'cek_ITH_Exists')
BEGIN
select COALESCE(sum(rcv_qty),0) as rcv_qty from RCV_TBL  where RCV_DONO=@Donoscan and RCV_ITMCD=@Item
END
IF ( @status = 'cek_ITH_Exists2')
BEGIN
--select COALESCE(sum(ITH_qty),0) as ITH_qty from ITH_TBL  where ITH_DOC=@Donoscan and ITH_ITMCD=@Item
select 1 as ITH_qty from ITH_TBL  where ITH_DOC=@Donoscan and ITH_ITMCD=@Item
END
IF ( @status = 'GetCountItem')
BEGIN
SELECT COUNT(*) FROM RCV_TBL WHERE RCV_DONO=@Donoscan group by RCV_ITMCD
END
IF ( @status = 'CekCancel')
BEGIN

SELECT * FROM RCVSCN_TBL WHERE RCVSCN_DONO=@Donoscan AND RCVSCN_ITMCD=@Item AND RCVSCN_SAVED IS NULL
END
IF ( @status = 'CancelScan')
BEGIN
DELETE FROM RCVSCN_TBL WHERE RCVSCN_DONO=@Donoscan AND RCVSCN_ITMCD=@Item AND RCVSCN_SAVED IS NULL
END

IF ( @status = 'checkmitm')
BEGIN
SELECT a.[RCV_ITMCD],a.[RCV_DONO],a.[RCV_QTY],sum(c.[RCVSCN_QTY]) as [RCVSCN_QTY],C.RCVSCN_LOCID,c.RCVSCN_USRID,
c.RCVSCN_SAVED,a.[RCV_WH]
 FROM [dbo].[RCV_TBL] a
   inner join [dbo].MITM_TBL b ON (a.[RCV_ITMCD] = b.MITM_ITMCD)
inner join [dbo].[RCVSCN_TBL] c ON (c.[RCVSCN_DONO] = a.[RCV_DONO] AND b.MITM_ITMCD = c.[RCVSCN_ITMCD])
  
WHERE
(
	a.[RCV_DONO]=@Donoscan  AND c.RCVSCN_ITMCD=@Item and c.RCVSCN_SAVED IS NULL

)
GROUP BY a.[RCV_ITMCD],a.[RCV_DONO],a.[RCV_QTY],C.RCVSCN_LOCID,c.RCVSCN_USRID,c.[RCVSCN_SAVED],a.[RCV_WH]
END

END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_rcvFG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sato_sp_spall_rcvFG]
@doc varchar(50),
@ser varchar(50),
@itmcd varchar(50),
@rack varchar(50),
@qty varchar(50),
@usr varchar(50),
@status varchar(50)
WITH RECOMPILE
AS
BEGIN
	SET NOCOUNT ON;

IF (@status ='checkith')
	BEGIN
		SELECT ITH_SER,SUM(ITH_QTY) ITH_QTY FROM ITH_TBL where ITH_WH='ARQA1' and ITH_SER=@ser --AND ITH_ITMCD='DD'
		GROUP BY ITH_SER HAVING SUM(ITH_QTY)>0;
	END 

IF (@status ='sumqty')
	BEGIN
		IF (@usr <>'')
			BEGIN
				SELECT count(*) AS TOTALBOX ,COALESCE(SUM(RCVFGSCN_SERQTY),0) as RCVFGSCN_SERQTY FROM RCVFGSCN_TBL
				WHERE RCVFGSCN_LOC=@rack AND RCVFGSCN_USRID=@usr AND RCVFGSCN_SAVED IS NULL;
			END
		ELSE IF (@usr = '')
			BEGIN
				SELECT count(*) AS TOTALBOX ,COALESCE(SUM(RCVFGSCN_SERQTY),0) as RCVFGSCN_SERQTY FROM RCVFGSCN_TBL
				WHERE RCVFGSCN_LOC=@rack AND RCVFGSCN_SAVED IS NULL;
			END
	END
--Sudah tidak terpakai CountTotalBox
IF (@status ='counttotalbox')
	BEGIN
		IF (@usr <>'')
		BEGIN
			SELECT * FROM RCVFGSCN_TBL
			WHERE RCVFGSCN_LOC=@rack AND RCVFGSCN_USRID=@usr AND RCVFGSCN_SAVED IS NULL;
		END
		ELSE IF (@usr = '')
		BEGIN
			SELECT * FROM RCVFGSCN_TBL
			WHERE RCVFGSCN_LOC=@rack AND RCVFGSCN_SAVED IS NULL;
		END
	END

IF (@status ='savetoscn')
	BEGIN
	INSERT INTO [dbo].[RCVFGSCN_TBL]
			   ([RCVFGSCN_ID]
			   ,[RCVFGSCN_DOC]
			   ,[RCVFGSCN_LOC]
			   ,[RCVFGSCN_SER]
			   ,[RCVFGSCN_ITMCD]
			   ,[RCVFGSCN_SERQTY]
			   ,[RCVFGSCN_LUPDT]
			   ,[RCVFGSCN_USRID])
		 VALUES
			   ((select [dbo].[sato_fun_get_id]('RcvFG'))
			   ,@doc
			   ,@rack
			   ,@ser
			   ,@itmcd
			   ,@qty
			   ,getdate()
			   ,@usr
			   );
	END
IF (@status ='checkScanSerial')
	BEGIN
		IF (@usr <>'')
			BEGIN
				SELECT * FROM RCVFGSCN_TBL
				WHERE RCVFGSCN_LOC=@rack and RCVFGSCN_SER=@ser AND RCVFGSCN_USRID=@usr AND RCVFGSCN_SAVED IS NULL;
			END
		ELSE IF (@usr = '')
			BEGIN
				SELECT * FROM RCVFGSCN_TBL
				WHERE RCVFGSCN_LOC=@rack and RCVFGSCN_SER=@ser AND RCVFGSCN_SAVED IS NULL;
			END
	END
IF (@status ='getdatascan')
	BEGIN
		IF (@usr <>'')
			BEGIN
				SELECT * FROM RCVFGSCN_TBL WHERE RCVFGSCN_LOC=@rack AND RCVFGSCN_USRID=@usr AND RCVFGSCN_SAVED IS NULL;
			END
		ELSE IF (@usr ='')
			BEGIN
				SELECT * FROM RCVFGSCN_TBL WHERE RCVFGSCN_LOC=@rack AND RCVFGSCN_SAVED IS NULL;
			END
	END

IF (@status ='canceldelete')
	BEGIN
		IF (@usr <>'')
			BEGIN
				DELETE FROM RCVFGSCN_TBL WHERE RCVFGSCN_LOC=@rack AND RCVFGSCN_USRID=@usr AND RCVFGSCN_SAVED IS NULL;
			END
		ELSE IF (@usr ='')
			BEGIN
			DELETE FROM RCVFGSCN_TBL WHERE RCVFGSCN_LOC=@rack  AND  RCVFGSCN_SAVED IS NULL;
			END
	END

IF (@status ='CheckITHExists')
	BEGIN
	SELECT top 1 ITH_DOC FROM ITH_TBL where ITH_SER=@ser AND ITH_FORM='INC-WH-FG'  ;
	END
IF (@status ='getDataSer')
	BEGIN
	SELECT SER_DOC,SER_QTY,SER_ITMID FROM SER_TBL WHERE SER_ID=@ser and '2'='2'--AND SER_ITMID='D';
	END

IF (@status ='getRackFG')
	BEGIN
	SELECT *  FROM [dbo].[MSTLOC_TBL] WHERE MSTLOC_CD=@rack;
	END
IF (@status ='getSerialFG')
	BEGIN
	SELECT SER_ITMID  FROM [dbo].[SER_TBL] WHERE SER_ID=@ser;
	END
IF (@status ='UpdateSavedSCN')
	BEGIN
	UPDATE RCVFGSCN_TBL SET RCVFGSCN_SAVED='1' WHERE RCVFGSCN_LOC=@rack AND RCVFGSCN_SER=@ser;
	END
IF (@status ='CheckRCVSCNSerial')
	BEGIN
	SELECT top 1 * FROM RCVFGSCN_TBL WHERE RCVFGSCN_SER=@ser;
	END
END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_scr_RM]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE procedure [dbo].[sato_sp_spall_scr_RM]
@doc varchar(50),
@itmcd varchar(50),
@lot varchar(50),
@status varchar(50)
AS
BEGIN
	SET NOCOUNT ON;

IF (@status ='countitem')
BEGIN
select A.SCRSCN_DOC,SCRSCN_ITMCD,SUM(A.SCRSCN_QTY) AS SCRSCN_QTY ,Z.SCR_QTY FROM SCRSCN_TBL A
INNER JOIN (SELECT SCR_DOC,SCR_ITMCD,SUM(SCR_QTY) AS SCR_QTY FROM  SCR_TBL WHERE SCR_DOC=@doc GROUP BY 
SCR_DOC,SCR_ITMCD) AS Z ON A.SCRSCN_DOC=Z.SCR_DOC AND A.SCRSCN_ITMCD=Z.SCR_ITMCD
WHERE A.SCRSCN_DOC=@doc and A.SCRSCN_SAVED='1'
GROUP BY A.SCRSCN_DOC,SCRSCN_ITMCD ,Z.SCR_QTY
HAVING SUM(A.SCRSCN_QTY)>=Z.SCR_QTY

END 

ELSE IF (@status ='checkitem')
BEGIN
select a.SCR_ITMCD,b.ITMLOC_LOC ,a.SCR_ITMLOT FROM SCR_TBL a ,
ITMLOC_TBL b  where a.SCR_ITMCD=b.ITMLOC_ITM and a.SCR_DOC=@doc and a.SCR_ITMCD=@itmcd
END

ELSE IF (@status='sumqtyscn')
BEGIN
select A.SCRSCN_DOC,A.SCRSCN_ITMCD,SUM(A.SCRSCN_QTY) AS SCRSCN_QTY ,Z.SCR_QTY FROM SCRSCN_TBL A
INNER JOIN (SELECT SCR_DOC,SCR_ITMCD,SUM(SCR_QTY) AS SCR_QTY FROM  SCR_TBL WHERE SCR_DOC=@doc AND 
SCR_ITMCD=@itmcd GROUP BY 
SCR_DOC,SCR_ITMCD) AS Z ON A.SCRSCN_DOC=Z.SCR_DOC AND A.SCRSCN_ITMCD=Z.SCR_ITMCD
WHERE A.SCRSCN_DOC=@doc AND A.SCRSCN_ITMCD=@itmcd GROUP BY A.SCRSCN_DOC,SCRSCN_ITMCD ,Z.SCR_QTY
END

ELSE IF (@status='forITH')
BEGIN
SELECT SCRSCN_ITMCD,SCRSCN_LOTNO,MAX(SCRSCN_DOC),SUM(SCRSCN_QTY),MAX(FORMAT(SCRSCN_LUPDT, 'yyyy-MM-dd HH:mm:ss')),MAX(SCRSCN_USRID) 
FROM SCRSCN_TBL WHERE SCRSCN_DOC=@doc
AND SCRSCN_ITMCD=@itmcd AND SCRSCN_SAVED IS NULL GROUP BY SCRSCN_ITMCD,SCRSCN_LOTNO
END

ELSE IF (@status='UpdatesavedSCRScn')
BEGIN

UPDATE SCRSCN_TBL SET SCRSCN_SAVED='1' WHERE SCRSCN_DOC=@doc AND SCRSCN_ITMCD=@itmcd AND SCRSCN_SAVED IS NULL;
END


else if (@status='checkcancel')
BEGIN
SELECT * FROM SCRSCN_TBL WHERE SCRSCN_DOC=@doc  AND SCRSCN_ITMCD=@itmcd AND SCRSCN_SAVED IS NULL
END

else if (@status='deletecancel')
BEGIN
DELETE FROM SCRSCN_TBL WHERE SCRSCN_DOC=@doc  AND SCRSCN_ITMCD=@itmcd  AND SCRSCN_SAVED IS NULL

END

else if (@status='checkFinish')
BEGIN
SELECT a.SCRSCN_DOC , a.SCRSCN_ITMCD , sum(a.SCRscn_qty) AS SCRSCN_QTY,z.SCR_QTY 
FROM SCRSCN_TBL a inner join 
(SELECT SCR_DOC , SCR_ITMCD,SUM(SCR_QTY) as SCR_QTY FROM  SCR_TBL WHERE SCR_DOC=@doc AND SCR_ITMCD=@itmcd
GROUP BY SCR_DOC , SCR_ITMCD) as z ON a.SCRSCN_DOC=z.SCR_DOC and a.SCRSCN_ITMCD=z.SCR_ITMCD
WHERE a.SCRSCN_DOC=@doc AND a.SCRSCN_ITMCD=@itmcd AND a.SCRSCN_SAVED is not null
GROUP BY a.SCRscn_doc , a.SCRscn_itmcd,z.SCR_QTY
HAVING sum(a.SCRscn_qty)>=z.SCR_QTY 
END
ELSE IF(@status = 'checkLot')
BEGIN
SELECT * FROM SCR_TBL WHERE SCR_DOC=@doc AND SCR_ITMCD=@itmcd AND SCR_ITMLOT=@lot
AND SCR_DOC NOT IN(SELECT SCR_DOC FROM SCRSCN_TBL WHERE SCRSCN_DOC=@doc AND SCRSCN_ITMCD=@itmcd 
AND SCRSCN_LOTNO=@lot)
END
ELSE IF(@status = 'GetsumQty')
BEGIN
SELECT sum(SCR_QTY) FROM SCR_TBL WHERE SCR_DOC=@doc AND SCR_ITMCD=@itmcd
END
ELSE IF(@status = 'GetCountItem')
BEGIN
SELECT SCR_DOC,SCR_ITMCD FROM SCR_TBL WHERE SCR_DOC=@doc Group By SCR_DOC,SCR_ITMCD
END
ELSE IF(@status = 'CheckDoc')
BEGIN

SELECT SCR_DOC FROM SCR_TBL WHERE SCR_DOC=@doc
END

END

GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_SCRSER_FG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sato_sp_spall_SCRSER_FG]
@doc varchar(50),
@ser varchar(50),
@itmcd varchar(50),
@status varchar(50)
AS
BEGIN
	SET NOCOUNT ON;


IF (@status ='countitem')
BEGIN
select A.SCRSCN_DOC,SCRSCN_ITMCD,SUM(A.SCRSCN_QTY) AS SCRSCN_QTY ,Z.SCRSER_QTY FROM SCRSCN_TBL A
INNER JOIN (SELECT a.SCRSER_DOC ,B.SER_ITMID  , SUM(a.SCRSER_QTY) as SCRSER_QTY from  SCRSER_TBL a , SER_TBL b
 WHERE A.SCRSER_SER=B.SER_ID and a.SCRSER_DOC=@doc
GROUP BY a.SCRSER_DOC ,B.SER_ITMID ) AS Z ON A.SCRSCN_DOC=Z.SCRSER_DOC AND A.SCRSCN_ITMCD=Z.SER_ITMID
WHERE A.SCRSCN_DOC=@doc and A.SCRSCN_SAVED='1' AND SCRSCN_SER IS NOT NULL
GROUP BY A.SCRSCN_DOC,A.SCRSCN_ITMCD ,Z.SCRSER_QTY
HAVING SUM(A.SCRSCN_QTY)>=Z.SCRSER_QTY

END 

ELSE IF (@status ='countItemSCRser')
BEGIN
SELECT a.SCRSER_DOC ,B.SER_ITMID FROM SCRSER_TBL a , SER_TBL b WHERE
a.SCRSER_DOC=@doc AND A.SCRSER_SER=B.SER_ID
GROUP BY a.SCRSER_DOC ,B.SER_ITMID
END 

ELSE IF (@status ='checkitem')
BEGIN
select b.SER_DOC,b.SER_ID,b.SER_ITMID,b.SER_QTY ,b.SER_LOTNO from SCRSER_TBL a ,
SER_TBL b  where a.SCRSER_SER=b.SER_ID and a.SCRSER_DOC=@doc and a.SCRSER_SER=@ser

END
else if (@status='sumqtySCRser')
BEGIN

--SELECT a.SCRSER_DOC ,B.SER_ITMID  , SUM(a.SCRSER_QTY) as SCRSER_QTY from  SCRSER_TBL a , SER_TBL b 
--WHERE A.SCRSER_SER=B.SER_ID AND a.SCRSER_DOC=@doc
--GROUP BY a.SCRSER_DOC ,B.SER_ITMID 
SELECT a.SCRSER_DOC ,max(B.SER_ITMID)SER_ITMID   , SUM(a.SCRSER_QTY) as SCRSER_QTY from  SCRSER_TBL a , SER_TBL b 
WHERE A.SCRSER_SER=B.SER_ID AND a.SCRSER_DOC=@doc
GROUP BY a.SCRSER_DOC 

END

ELSE IF (@status='sumqtyscn')
BEGIN
--select A.SCRSCN_DOC,A.SCRSCN_ITMCD,SUM(A.SCRSCN_QTY) AS SCRSCN_QTY ,Z.SCRSER_QTY FROM SCRSCN_TBL A
--INNER JOIN (SELECT a.SCRSER_DOC ,B.SER_ITMID  , SUM(a.SCRSER_QTY) as SCRSER_QTY from  SCRSER_TBL a , SER_TBL b
-- WHERE A.SCRSER_SER=B.SER_ID and a.SCRSER_DOC=@doc
--GROUP BY a.SCRSER_DOC ,B.SER_ITMID) AS Z ON A.SCRSCN_DOC=Z.SCRSER_DOC AND A.SCRSCN_ITMCD=Z.SER_ITMID
--WHERE A.SCRSCN_DOC=@doc GROUP BY A.SCRSCN_DOC,SCRSCN_ITMCD ,Z.SCRSER_QTY

select A.SCRSCN_DOC,MAX(A.SCRSCN_ITMCD) AS SCRSCN_ITMCD ,SUM(A.SCRSCN_QTY) AS SCRSCN_QTY  FROM SCRSCN_TBL A
WHERE A.SCRSCN_DOC=@doc GROUP BY A.SCRSCN_DOC
END

ELSE IF (@status='forITH')
BEGIN
SELECT SCRSCN_ITMCD,SCRSCN_SER,MAX(SCRSCN_DOC) as SCRSCN_DOC,SUM(SCRSCN_QTY) as SCRSCN_QTY,MAX(FORMAT(SCRSCN_LUPDT, 'yyyy-MM-dd HH:mm:ss')) as SCRSCN_LUPDT,MAX(SCRSCN_USRID) as SCRSCN_USRID
FROM SCRSCN_TBL WHERE SCRSCN_DOC=@doc
 AND SCRSCN_SAVED IS NULL 
GROUP BY SCRSCN_ITMCD,SCRSCN_SER
END

ELSE IF (@status='UpdatesavedSCRScn')
BEGIN

UPDATE SCRSCN_TBL SET SCRSCN_SAVED='1' WHERE SCRSCN_DOC=@doc AND SCRSCN_SAVED IS NULL;

END


else if (@status='checkcancel')
BEGIN
SELECT * FROM SCRSCN_TBL WHERE SCRSCN_DOC=@doc  AND SCRSCN_ITMCD=@itmcd AND SCRSCN_SAVED IS NULL
END

else if (@status='deletecancel')
BEGIN
DELETE FROM SCRSCN_TBL WHERE SCRSCN_DOC=@doc  AND SCRSCN_ITMCD=@itmcd  AND SCRSCN_SAVED IS NULL

END
else if (@status='checkFinish')
BEGIN

--select A.SCRSCN_DOC,A.SCRSCN_ITMCD,SUM(A.SCRSCN_QTY) AS SCRSCN_QTY ,Z.SCRSER_QTY FROM SCRSCN_TBL A
--INNER JOIN (SELECT a.SCRSER_DOC ,B.SER_ITMID  , SUM(a.SCRSER_QTY) as SCRSER_QTY from  SCRSER_TBL a , SER_TBL b
-- WHERE A.SCRSER_SER=B.SER_ID and a.SCRSER_DOC=@doc
--GROUP BY a.SCRSER_DOC ,B.SER_ITMID) AS Z ON A.SCRSCN_DOC=Z.SCRSER_DOC AND A.SCRSCN_ITMCD=Z.SER_ITMID
--WHERE A.SCRSCN_DOC=@doc 
--GROUP BY A.SCRSCN_DOC,SCRSCN_ITMCD ,Z.SCRSER_QTY
--HAVING SUM(A.SCRSCN_QTY)>=Z.SCRSER_QTY

Select A.SCRSCN_DOC,MAX(A.SCRSCN_ITMCD) AS SCRSCN_ITMCD,SUM(A.SCRSCN_QTY) AS SCRSCN_QTY ,Z.SCRSER_QTY FROM SCRSCN_TBL A
JOIN (SELECT a.SCRSER_DOC ,MAX(B.SER_ITMID) AS SER_ITMID  , SUM(a.SCRSER_QTY) as SCRSER_QTY from  SCRSER_TBL a , SER_TBL b
WHERE A.SCRSER_SER=B.SER_ID and a.SCRSER_DOC=@doc
GROUP BY a.SCRSER_DOC) AS Z ON A.SCRSCN_DOC=Z.SCRSER_DOC
WHERE A.SCRSCN_DOC=@doc
GROUP BY A.SCRSCN_DOC,SCRSCN_ITMCD ,Z.SCRSER_QTY HAVING SUM(A.SCRSCN_QTY)>=Z.SCRSER_QTY;

END 
else if (@status='GetRack')
BEGIN
SELECT ITMLOC_LOC from  ITMLOC_TBL  WHERE ITMLOC_ITM IN(SELECT SER_ITMID FROM SER_TBL where SER_ID=@ser)

END

ELSE IF(@status = 'checkserialexists')

BEGIN

SELECT * FROM SCRSCN_TBL WHERE SCRSCN_DOC=@doc  AND SCRSCN_SER=@ser;

END
ELSE IF(@status = 'CheckDoc')

BEGIN
SELECT SCRSER_DOC FROM SCRSER_TBL WHERE SCRSER_DOC=@doc

END


END


GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_trflocFG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sato_sp_spall_trflocFG]
@ser varchar(50),
@fromrack varchar(50),
@fromwh varchar(50),
@towh varchar(50),
@torack varchar(50),
@status varchar(50)
AS
BEGIN
	SET NOCOUNT ON;
IF (@status ='checkstock')
BEGIN
select ITH_ITMCD, ITH_SER,sum(ith_qty) from ITH_TBL where ITH_WH=@fromwh 
and ITH_SER=@ser AND ITH_FORM NOT IN ('SASTART','SA') GROUP BY ITH_ITMCD,ITH_SER having sum(ITH_QTY)>0;
END 

IF (@status ='checkSER')
BEGIN

SELECT top 1 a.SER_ID,a.SER_QTY,a.SER_DOC,a.SER_LOTNO ,a.SER_ITMID ,b.ITH_LOC ,
b.ITH_LUPDT
FROM SER_TBL a inner join 
ITH_TBL b ON a.SER_ID=b.ITH_SER
WHERE a.SER_ID=@ser AND b.ITH_WH=@fromwh and ith_qty>0 AND ITH_FORM NOT IN ('SASTART','SA') ORDER BY ITH_LUPDT desc;

END 
IF (@status ='checktoWH')
BEGIN
select * from MSTLOC_TBL a , MSTLOCG_TBL b  where a.MSTLOC_GRP=b.MSTLOCG_ID AND a.MSTLOC_GRP=@towh;
END
IF (@status ='checktoRack')
BEGIN
select * from MSTLOC_TBL where MSTLOC_GRP=@towh AND MSTLOC_CD=@torack;
END
IF (@status ='updatesaved')
BEGIN
UPDATE TRFFGSCN_TBL SET TRFFGSCN_SAVED='1',TRFFGSCN_TOWH=@towh , TRFFGSCN_TORACK=@torack WHERE TRFFGSCN_SAVED IS NULL
END

IF (@status ='canceldelete')
BEGIN
DELETE FROM TRFFGSCN_TBL WHERE TRFFGSCN_SAVED IS NULL;
END
IF (@status ='checkserialexists')
BEGIN
SELECT COUNT(*) FROM TRFFGSCN_TBL WHERE TRFFGSCN_SER=@ser AND TRFFGSCN_SAVED IS NULL
END

IF (@status ='GetDataSCN')
BEGIN
SELECT * FROM TRFFGSCN_TBL WHERE TRFFGSCN_SAVED IS NULL;
END
IF (@status ='GetTotalbox')
BEGIN
SELECT ISNULL(COUNT(*),0) FROM TRFFGSCN_TBL WHERE  TRFFGSCN_SAVED IS NULL;
END
IF (@status ='Getsumqty')
BEGIN
SELECT ISNULL(SUM(TRFFGSCN_SERQTY),0) FROM TRFFGSCN_TBL WHERE  TRFFGSCN_SAVED IS NULL
END
END;


GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_trflocRM]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sato_sp_spall_trflocRM]
@itmcd varchar(50),
@fromrack varchar(50),
@fromwh varchar(50),
@towh varchar(50),
@torack varchar(50),
@status varchar(50)
AS
BEGIN
	SET NOCOUNT ON;

	IF (@status ='checkstock')
BEGIN

 SELECT a.MITM_ITMCD,MAX(b.ITH_LOC) as ITH_LOC,SUM(ITH_QTY) AS ITH_QTY FROM MITM_TBL a inner join 
ITH_TBL b ON a.MITM_ITMCD=b.ITH_ITMCD
 WHERE MITM_ITMCD=@itmcd AND ITH_WH=@fromwh GROUP BY a.MITM_ITMCD,b.ITH_LUPDT HAVING SUM(ITH_QTY) > 0;


END 

IF (@status ='checkitemmitm')
BEGIN

-- SELECT TOP 1 a.MITM_ITMCD,b.ITH_LOC as ITH_LOC FROM MITM_TBL a inner join 
--ITH_TBL b ON a.MITM_ITMCD=b.ITH_ITMCD
-- WHERE MITM_ITMCD=@itmcd AND ITH_WH=@fromwh ORDER BY b.ITH_LUPDT DESC;

 SELECT TOP 1 a.MITM_ITMCD,ITMLOC_LOC as ITH_LOC FROM MITM_TBL a inner join 
ITH_TBL b ON a.MITM_ITMCD=b.ITH_ITMCD
LEFT JOIN ITMLOC_TBL ON MITM_ITMCD=ITMLOC_ITM
 WHERE MITM_ITMCD=@itmcd AND ITH_WH=@fromwh ORDER BY b.ITH_LUPDT DESC;


END 
IF (@status ='checktoWH')
BEGIN
select * from MSTLOC_TBL a , MSTLOCG_TBL b  where a.MSTLOC_GRP=b.MSTLOCG_ID AND a.MSTLOC_GRP=@towh;
END
IF (@status ='checktoRack')
BEGIN
select * from MSTLOC_TBL where MSTLOC_GRP=@towh AND MSTLOC_CD=@torack;
END

END
GO
/****** Object:  StoredProcedure [dbo].[sato_sp_spall_trflocToSCNFG]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[sato_sp_spall_trflocToSCNFG]
(
@TRFFGSCN_LOC varchar(50),
@TRFFGSCN_SER varchar(50),
@TRFFGSCN_LOT varchar(50),
@TRFFGSCN_ITMCD varchar(50),
@TRFFGSCN_FROMWH varchar(50),
@TRFFGSCN_SERQTY decimal(12,3),
@TRFFGSCN_LUPDT datetime,
@TRFFGSCN_USRID varchar(50)
)
AS
BEGIN
	SET NOCOUNT ON;


INSERT INTO [dbo].[TRFFGSCN_TBL]([TRFFGSCN_ID],[TRFFGSCN_LOC],[TRFFGSCN_SER] ,[TRFFGSCN_LOT],[TRFFGSCN_ITMCD]
,[TRFFGSCN_FROMWH],[TRFFGSCN_SERQTY],[TRFFGSCN_LUPDT],[TRFFGSCN_USRID])
     VALUES (
(select [dbo].sato_fun_get_id('TransferLocSCNFG')),
@TRFFGSCN_LOC ,
@TRFFGSCN_SER ,
@TRFFGSCN_LOT ,
@TRFFGSCN_ITMCD ,
@TRFFGSCN_FROMWH ,
@TRFFGSCN_SERQTY,
GETDATE(),
@TRFFGSCN_USRID
)
END	


GO
/****** Object:  StoredProcedure [dbo].[sato_sp_UPDATE_USER_HT]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[sato_sp_UPDATE_USER_HT]
       @userid                      VARCHAR(10)  = NULL   , 
       @passwd                      VARCHAR(8)  = NULL   , 
       @name                        VARCHAR(20)  = NULL   , 
       @Authority                   VARCHAR(12)  = NULL  ,
	   @olduserid                   VARCHAR(10)  = NULL 
	  
AS 
BEGIN 
     SET NOCOUNT ON 
	  
	 	UPDATE User_ht SET UserId=@userid,Passwd=@passwd,Name=@name,Authority=@Authority WHERE UserId=@olduserid
end







GO
/****** Object:  StoredProcedure [dbo].[sato_sp_VerQTYScanNQTYRemain]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[sato_sp_VerQTYScanNQTYRemain]
	@Donoscan 	[varchar](100),
	@loc 	[varchar](50),
	@Item 	[varchar](50)
AS
BEGIN
	SET NOCOUNT ON;

SELECT a.[RCV_ITMCD],a.[RCV_DONO],a.[RCV_QTY],b.[ITMLOC_LOC],b.[ITMLOC_USRID]
FROM [dbo].[RCV_TBL] a
inner join [dbo].[ITMLOC_TBL] b ON (a.[RCV_ITMCD] = b.[ITMLOC_ITM])
WHERE a.[RCV_DONO] = @Donoscan AND b.[ITMLOC_LOC] = @loc AND a.[RCV_ITMCD] = @Item
GROUP BY a.[RCV_ITMCD],a.[RCV_DONO],a.[RCV_QTY],b.[ITMLOC_LOC],b.[ITMLOC_USRID]


END



GO
/****** Object:  StoredProcedure [dbo].[sp_check_sususan_bahan_baku]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--SELECT * FROM SERD_TBL where SERD_JOB='20-YT26-219643909'


CREATE PROCEDURE [dbo].[sp_check_sususan_bahan_baku]
@location varchar(15)
as
SELECT V1.*,
        SER_DOC,
        SER_ITMID,
        MYPER,
        ROUND(ISNULL(SERD2_QTPER,
        0),1) CALPER,
        ISNULL(SER_RMUSE_COMFG,
        '-') FLG,RTRIM(MITM_ITMD1) MITM_ITMD1,SER_QTY
FROM 
    (SELECT ITH_SER
    FROM ITH_TBL
    WHERE ITH_WH=@location
    GROUP BY ITH_SER
    HAVING SUM(ITH_QTY)>0) V1
LEFT JOIN SER_TBL
    ON ITH_SER=SER_ID
LEFT JOIN 
    (SELECT PIS3_WONO,
        SUM(MYPER) MYPER
    FROM 
        (SELECT 
        PIS3_WONO,
        PIS3_LINENO,
        PIS3_FR,
        PIS3_PROCD,
        PIS3_MC,
        PIS3_MCZ,
        SUM(PIS3_REQQT) PIS3_REQQTSUM,
        SUM(PIS3_REQQT)/SIMQT MYPER,
        max(PIS3_ITMCD) PIS3_ITMCD,
        PIS3_MPART
        FROM XPIS3        
        RIGHT JOIN 
            (SELECT PPSN1_WONO,
        MAX(PPSN1_DOCNO) PSN1DOCNO, MAX(PPSN1_SIMQT) SIMQT
            FROM XPPSN1
            GROUP BY  PPSN1_WONO ) VPSN
                ON PIS3_WONO=PPSN1_WONO
                    AND PIS3_DOCNO=PSN1DOCNO
            GROUP BY  PIS3_WONO,PIS3_LINENO,PIS3_MC,PIS3_MCZ,SIMQT,PIS3_FR,PIS3_PROCD,PIS3_MPART) V1
            GROUP BY  PIS3_WONO ) VREQ
            ON SER_DOC=PIS3_WONO
    LEFT JOIN 
    (SELECT SERD2_JOB,
        SUM(SERD2_QTY)/MAX(SERD2_FGQTY) SERD2_QTPER,
        max(SERD2_SER) SERD2_SER,
        max(SERD2_FGQTY) SERD2_FGQTY
    FROM SERD2_TBL SERDA
	WHERE SERD2_SER IN (SELECT ITH_SER
    FROM ITH_TBL
    WHERE ITH_WH=@location
    GROUP BY  ITH_SER
    HAVING SUM(ITH_QTY)>0)
    GROUP BY  SERD2_JOB,SERD2_SER ) VCAL
    ON ITH_SER=SERD2_SER
LEFT JOIN MITM_TBL
    ON SER_ITMID=MITM_ITMCD
WHERE (ISNULL(MYPER,0) > ROUND(ISNULL(SERD2_QTPER,0),1) and ISNULL(SER_RMUSE_COMFG,'-')='-' ) 
or (ISNULL(MYPER,0)=0 and ISNULL(SER_RMUSE_COMFG,'-')='-' and SER_DOC NOT LIKE '%-C%')
and SER_QTY>0
ORDER BY  SER_DOC



GO
/****** Object:  StoredProcedure [dbo].[sp_check_sususan_bahan_baku_by_job]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_check_sususan_bahan_baku_by_job]
@jobno varchar(50)
as
SET NOCOUNT ON 
SELECT ITH_SER,
        SER_DOC,
        SER_ITMID,
        MYPER,
        ROUND(ISNULL(SERD2_QTPER,
        0),1) CALPER,
        ISNULL(RTRIM(SER_RMUSE_COMFG),
        '-') FLG,RTRIM(MITM_ITMD1) MITM_ITMD1,SER_QTY
FROM 
     (SELECT SER_ID ITH_SER,SER_QTY,SER_ITMID,SER_RMUSE_COMFG,SER_DOC FROM SER_TBL WHERE  SER_DOC=@jobno   ) V1
LEFT JOIN 
    (SELECT PIS3_WONO,
        SUM(MYPER) MYPER
    FROM 
        (SELECT 
        PIS3_WONO,
        PIS3_LINENO,
        PIS3_FR,
        PIS3_PROCD,
        PIS3_MC,
        PIS3_MCZ,
        SUM(PIS3_REQQT) PIS3_REQQTSUM,
        SUM(PIS3_REQQT)/SIMQT MYPER,
        max(PIS3_ITMCD) PIS3_ITMCD,
        PIS3_MPART
        FROM XPIS3       
        RIGHT JOIN 
            (SELECT PPSN1_WONO,
        MAX(PPSN1_DOCNO) PSN1DOCNO, MAX(PPSN1_SIMQT) SIMQT
            FROM XPPSN1
			WHERE PPSN1_WONO=@jobno
            GROUP BY  PPSN1_WONO ) VPSN
                ON PIS3_WONO=PPSN1_WONO
                    AND PIS3_DOCNO=PSN1DOCNO			
            GROUP BY  PIS3_WONO,PIS3_LINENO,PIS3_MC,PIS3_MCZ,SIMQT,PIS3_FR,PIS3_PROCD,PIS3_MPART) V1
            GROUP BY  PIS3_WONO ) VREQ
            ON SER_DOC=PIS3_WONO 
LEFT JOIN 
    (SELECT SERD2_JOB,
        SUM(SERD2_QTY)/MAX(SERD2_FGQTY) SERD2_QTPER,
        max(SERD2_SER) SERD2_SER,
        max(SERD2_FGQTY) SERD2_FGQTY
    FROM SERD2_TBL SERDA
	WHERE SERD2_JOB=@jobno
    GROUP BY  SERD2_JOB,SERD2_SER ) VCAL
    ON ITH_SER=SERD2_SER
LEFT JOIN MITM_TBL
    ON SER_ITMID=MITM_ITMCD

ORDER BY  SER_DOC
GO
/****** Object:  StoredProcedure [dbo].[sp_check_sususan_bahan_baku_by_txid]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_check_sususan_bahan_baku_by_txid]
@txid varchar(50)
as
SET NOCOUNT ON 
SELECT ITH_SER,
        SER_DOC,
        SER_ITMID,
        MYPER,
       ROUND(ISNULL(SERD2_QTPER,
        0),1) CALPER,
        RTRIM(ISNULL(SER_RMUSE_COMFG,
        '-')) FLG,RTRIM(MITM_ITMD1) MITM_ITMD1,SER_QTY
FROM 
     (SELECT SER_ID ITH_SER,SER_QTY,SER_ITMID,SER_RMUSE_COMFG,SER_DOC FROM DLV_TBL left join SER_TBL on DLV_SER=SER_ID WHERE  DLV_ID=@txid) V1
LEFT JOIN 
    (SELECT PIS3_WONO,
        SUM(MYPER) MYPER
    FROM 
        (SELECT 
        PIS3_WONO,
        PIS3_LINENO,
        PIS3_FR,
        PIS3_PROCD,
        PIS3_MC,
        PIS3_MCZ,
        SUM(PIS3_REQQT) PIS3_REQQTSUM,
        SUM(PIS3_REQQT)/SIMQT MYPER,
        max(PIS3_ITMCD) PIS3_ITMCD,
        PIS3_MPART
        FROM XPIS3        
        RIGHT JOIN 
            (SELECT PPSN1_WONO,
        MAX(PPSN1_DOCNO) PSN1DOCNO, MAX(PPSN1_SIMQT) SIMQT
            FROM XPPSN1			
            GROUP BY  PPSN1_WONO ) VPSN
                ON PIS3_WONO=PPSN1_WONO
                    AND PIS3_DOCNO=PSN1DOCNO		
			WHERE PPSN1_WONO IN (SELECT DISTINCT SER_DOC FROM DLV_TBL left join SER_TBL on DLV_SER=SER_ID WHERE  DLV_ID=@txid)
            GROUP BY  PIS3_WONO,PIS3_LINENO,PIS3_MC,PIS3_MCZ,SIMQT,PIS3_FR,PIS3_PROCD,PIS3_MPART) V1
            GROUP BY  PIS3_WONO ) VREQ
            ON SER_DOC=PIS3_WONO 
    LEFT JOIN 
    (SELECT SERD2_JOB,
        SUM(SERD2_QTY)/MAX(SERD2_FGQTY) SERD2_QTPER,
        max(SERD2_SER) SERD2_SER,
        max(SERD2_FGQTY) SERD2_FGQTY
    FROM SERD2_TBL SERDA	
	WHERE SERD2_SER IN (SELECT DLV_SER FROM DLV_TBL WHERE DLV_ID=@txid)
    GROUP BY  SERD2_JOB,SERD2_SER ) VCAL
    ON ITH_SER=SERD2_SER
LEFT JOIN MITM_TBL
    ON SER_ITMID=MITM_ITMCD
ORDER BY  SER_DOC
GO
/****** Object:  StoredProcedure [dbo].[sp_check_sususan_bahan_baku_by_txid_nonmcz]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_check_sususan_bahan_baku_by_txid_nonmcz]
@txid varchar(50)
as
SET NOCOUNT ON 
SELECT ITH_SER,
        SER_DOC,
        SER_ITMID,
        MYPER,
       ROUND(ISNULL(SERD2_QTPER,
        0),1) CALPER,
        RTRIM(ISNULL(SER_RMUSE_COMFG,
        '-')) FLG,RTRIM(MITM_ITMD1) MITM_ITMD1,SER_QTY
FROM 
     (SELECT SER_ID ITH_SER,SER_QTY,SER_ITMID,SER_RMUSE_COMFG,SER_DOC FROM DLV_TBL left join SER_TBL on DLV_SER=SER_ID WHERE  DLV_ID=@txid) V1
LEFT JOIN 
    (SELECT PIS3_WONO,
        SUM(MYPER) MYPER
    FROM 
        (SELECT 
        PIS3_WONO,               
        SUM(PIS3_REQQT)/SIMQT MYPER,        
        PIS3_MPART
        FROM XPIS3        
        RIGHT JOIN 
            (SELECT PPSN1_WONO,
        MAX(PPSN1_DOCNO) PSN1DOCNO, MAX(PPSN1_SIMQT) SIMQT
            FROM XPPSN1			
            GROUP BY  PPSN1_WONO ) VPSN
                ON PIS3_WONO=PPSN1_WONO
                    AND PIS3_DOCNO=PSN1DOCNO		
			WHERE PPSN1_WONO IN (SELECT DISTINCT SER_DOC FROM DLV_TBL left join SER_TBL on DLV_SER=SER_ID WHERE  DLV_ID=@txid)
            GROUP BY  PIS3_WONO,SIMQT,PIS3_MPART) V1
            GROUP BY  PIS3_WONO ) VREQ
            ON SER_DOC=PIS3_WONO 
    LEFT JOIN 
    (SELECT SERD2_JOB,
        SUM(SERD2_QTY)/MAX(SERD2_FGQTY) SERD2_QTPER,
        max(SERD2_SER) SERD2_SER,
        max(SERD2_FGQTY) SERD2_FGQTY
    FROM SERD2_TBL SERDA	
	WHERE SERD2_SER IN (SELECT DLV_SER FROM DLV_TBL WHERE DLV_ID=@txid)
    GROUP BY  SERD2_JOB,SERD2_SER ) VCAL
    ON ITH_SER=SERD2_SER
LEFT JOIN MITM_TBL
    ON SER_ITMID=MITM_ITMCD
ORDER BY  SER_DOC
GO
/****** Object:  StoredProcedure [dbo].[sp_check_sususan_bahan_baku_by_txid_v2]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_check_sususan_bahan_baku_by_txid_v2]
@txid varchar(50)
as
SET NOCOUNT ON 
SELECT ITH_SER,
        SER_DOC,
        SER_ITMID,
        MYPER,
       ROUND(ISNULL(SERD2_QTPER,
        0),1) CALPER,
        RTRIM(ISNULL(SER_RMUSE_COMFG,
        '-')) FLG,RTRIM(MITM_ITMD1) MITM_ITMD1,SER_QTY
FROM 
     (SELECT SER_ID ITH_SER,SER_QTY,SER_ITMID,SER_RMUSE_COMFG,SER_DOC FROM DLV_TBL left join SER_TBL on DLV_SER=SER_ID WHERE  DLV_ID=@txid) V1
LEFT JOIN 
    (SELECT WOH_CD PIS3_WONO,
        WOH_TTLUSE MYPER
    FROM 
        WOH_TBL ) VREQ
            ON SER_DOC=PIS3_WONO 
    LEFT JOIN 
    (SELECT SERD2_JOB,
        SUM(SERD2_QTY)/MAX(SERD2_FGQTY) SERD2_QTPER,
        max(SERD2_SER) SERD2_SER,
        max(SERD2_FGQTY) SERD2_FGQTY
    FROM SERD2_TBL SERDA	
	WHERE SERD2_SER IN (SELECT DLV_SER FROM DLV_TBL WHERE DLV_ID=@txid)
    GROUP BY  SERD2_JOB,SERD2_SER ) VCAL
    ON ITH_SER=SERD2_SER
LEFT JOIN MITM_TBL
    ON SER_ITMID=MITM_ITMCD
ORDER BY  SER_DOC
GO
/****** Object:  StoredProcedure [dbo].[sp_comparejobreq_vs_act]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

create procedure [dbo].[sp_comparejobreq_vs_act]
@pjob varchar(50)
as
SELECT VX.*,SERD_MCZ,SERD_ITMCD FROM
(select PDPP_MDLCD,PIS3_WONO,PIS3_LINENO,PIS3_FR,PIS3_PROCD,PIS3_MC,PIS3_MCZ,SUM(PIS3_REQQT) PIS3_REQQTSUM,SUM(PIS3_REQQT)/PDPP_WORQT MYPER,max(PIS3_MPART) PIS3_MPART, max(PIS3_ITMCD) PIS3_ITMCD from XPIS3 
        INNER JOIN XWO ON PIS3_WONO=PDPP_WONO
        WHERE PIS3_WONO=@pjob
        GROUP BY PIS3_WONO,PIS3_LINENO,PIS3_MC,PIS3_MCZ,PDPP_WORQT,PDPP_MDLCD,PIS3_FR,PIS3_PROCD) VX
LEFT JOIN (
SELECT SERD_JOB,SERD_LINENO,SERD_MC,SERD_MCZ,SERD_QTYREQ,SERD_FR,SERD_PROCD,SUM(SERD_QTY) SUPQTY,max(SERD_ITMCD) SERD_ITMCD FROM SERD_TBL WHERE SERD_JOB=@pjob
GROUP BY SERD_JOB,SERD_LINENO,SERD_MC,SERD_MCZ,SERD_QTYREQ,SERD_FR,SERD_PROCD,SERD_QTYREQ
) VS ON PIS3_WONO=SERD_JOB AND PIS3_LINENO=SERD_LINENO AND PIS3_MC=SERD_MC AND PIS3_MCZ=SERD_MCZ AND PIS3_PROCD=SERD_PROCD AND PIS3_FR=SERD_FR
        ORDER BY VX.PIS3_MCZ,VX.PIS3_MC,VX.PIS3_PROCD
GO
/****** Object:  StoredProcedure [dbo].[sp_customer_ost_so]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create procedure [dbo].[sp_customer_ost_so]
@bg varchar(25)
as
SELECT V1.*,MCUS_CUSNM FROM
(SELECT SSO2_CUSCD FROM XSSO2 
WHERE SSO2_BSGRP=@bg AND SSO2_COMFG='0' AND SSO2_CPOTYPE='CPO'
GROUP BY SSO2_CUSCD) V1 inner join XMCUS on SSO2_CUSCD=MCUS_CUSCD
GO
/****** Object:  StoredProcedure [dbo].[sp_customer_so]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_customer_so]
@bg varchar(25)
as
SELECT V1.*,MCUS_CUSNM FROM
(SELECT SSO2_CUSCD FROM XSSO2 
WHERE SSO2_BSGRP=@bg  AND SSO2_CPOTYPE='CPO'
GROUP BY SSO2_CUSCD) V1 inner join XMCUS on SSO2_CUSCD=MCUS_CUSCD
order by MCUS_CUSNM asc

GO
/****** Object:  StoredProcedure [dbo].[sp_getbomfrom_DO]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sp_getbomfrom_DO]
@dono varchar(45)
as
SET NOCOUNT ON 

SELECT SER_ITMID,SERD2_FGQTY,SERD2_ITMCD,SERD2_SER,SER_DOC,sum(MYPER) MYPER,MITM_MODEL FROM 
(
SELECT VX.SERD2_PSNNO,VX.SERD2_LINENO,VX.SERD2_PROCD,VX.SERD2_CAT,VX.SERD2_FR,VX.SERD2_ITMCD, SERD2_QTYSUM,VX.SERD2_FGQTY, RTRIM(MITM_ITMD1) MITM_ITMD1, RTRIM(MITM_SPTNO) MITM_SPTNO 
, CASE WHEN CHARINDEX('IEI',VX.SERD2_PSNNO) >0 THEN CONVERT(FLOAT,SERD2_QTYSUM)/SERD2_FGQTY ELSE VX.SERD2_QTPER END MYPER, VX.SERD2_MC, VX.SERD2_MCZ,VX.SERD2_SER,SER_ITMID,SER_DOC,MITM_MODEL FROM
        (
        SELECT SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_PROCD,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_FGQTY,SERD2_MCZ,SERD2_QTPER,SUM(SERD2_QTY) SERD2_QTYSUM,SERD2_ITMCD,SERD2_SER  FROM SERD2_TBL WHERE SERD2_SER in (select DLV_SER from DLV_TBL where DLV_ID=@dono)
        GROUP BY SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_MCZ,SERD2_QTPER,SERD2_ITMCD,SERD2_FGQTY,SERD2_PROCD,SERD2_SER) VX
        INNER JOIN
        (
        SELECT SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_PROCD,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_MCZ,SERD2_QTPER,SUM(SERD2_QTY) QTYPERMC,SERD2_SER FROM SERD2_TBL WHERE SERD2_SER in (select DLV_SER from DLV_TBL where DLV_ID=@dono)
        GROUP BY SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_MCZ,SERD2_QTPER,SERD2_PROCD,SERD2_SER
        ) VXH ON VX.SERD2_PSNNO=VXH.SERD2_PSNNO AND VX.SERD2_JOB=VXH.SERD2_JOB AND VX.SERD2_LINENO=VXH.SERD2_LINENO AND VX.SERD2_CAT=VXH.SERD2_CAT AND VX.SERD2_FR=VXH.SERD2_FR
        AND VX.SERD2_MC=VXH.SERD2_MC AND VX.SERD2_MCZ=VXH.SERD2_MCZ AND VX.SERD2_QTPER=VXH.SERD2_QTPER AND VX.SERD2_PROCD=VXH.SERD2_PROCD and vx.SERD2_SER=VXH.SERD2_SER        
		LEFT JOIN SER_TBL ON VX.SERD2_SER=SER_ID
		LEFT JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD
		) VFIN		
		GROUP BY SERD2_ITMCD,SERD2_FGQTY,SERD2_SER,SER_ITMID,SER_DOC,MITM_MODEL
        ORDER BY SERD2_SER,SERD2_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[sp_getgreatersothansi]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_getgreatersothansi] as


SELECT SISO_TBL.*,0 SISO_QTYPLOT,SI_QTY,SI_ITMCD FROM SISO_TBL
LEFT JOIN
(select SI_LINENO,SI_QTY,SI_ITMCD FROM SI_TBL
LEFT JOIN 
(
SELECT SISO_HLINE,SUM(SISO_QTY) SOQTY,COUNT(*) TTLPLOT FROM SISO_TBL 
GROUP BY SISO_HLINE
) V1 ON SI_LINENO=SISO_HLINE
WHERE SI_QTY> ISNULL(SOQTY,0) AND TTLPLOT IS NOT NULL) V2
 ON SISO_HLINE=SI_LINENO
 ORDER BY SI_LINENO ASC
GO
/****** Object:  StoredProcedure [dbo].[sp_getline_mfg]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sp_getline_mfg] 
@job varchar(50)
as
select PPSN1_LINENO from XPPSN1
where PPSN1_LINENO !=''
group by PPSN1_LINENO
order by PPSN1_LINENO asc
GO
/****** Object:  StoredProcedure [dbo].[sp_getreturnbalance_peritem]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--SELECT * FROM SPLSCN_TBL WHERE SPLSCN_DOC='SP-IEI-2020-12-1069' and SPLSCN_CAT='CHIP' -- AND SPL_ITMCD LIKE '%219%'
CREATE PROCEDURE [dbo].[sp_getreturnbalance_peritem]
@psn varchar(50),
@line varchar(50),
@category varchar(50),
@itemcode varchar(50)
AS

SELECT VSCAN.*, ISNULL(RETQTY,0) RETQTY, SCNQTY-ISNULL(RETQTY,0) BALQTY FROM
(select SPLSCN_ITMCD,sum(SPLSCN_QTY) SCNQTY from SPLSCN_TBL where SPLSCN_DOC=@psn and SPLSCN_LINE=@line and SPLSCN_CAT=@category and SPLSCN_ITMCD=@itemcode
group by SPLSCN_ITMCD) VSCAN
LEFT JOIN
(
select RETSCN_ITMCD,sum(RETSCN_QTYAFT) RETQTY from RETSCN_TBL where RETSCN_SPLDOC=@psn and RETSCN_LINE=@line and RETSCN_CAT=@category and RETSCN_ITMCD=@itemcode
group by RETSCN_ITMCD
) VRET  ON VSCAN.SPLSCN_ITMCD=VRET.RETSCN_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[sp_getstock_scrap]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_getstock_scrap] as
select ITH_ITMCD, MITM_ITMD1,sum(ITH_QTY) ITH_QTY from ITH_TBL a INNER JOIN MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD WHERE ITH_WH ='ARWH9SC'
GROUP BY ITH_ITMCD,MITM_ITMD1
order by ITH_ITMCD ASC

GO
/****** Object:  StoredProcedure [dbo].[sp_getstock_scrap_fg]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_getstock_scrap_fg] as
select ITH_SER,ITH_ITMCD, MITM_ITMD1,sum(ITH_QTY) ITH_QTY from ITH_TBL a INNER JOIN MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD 
WHERE ITH_WH ='AFWH9SC'
GROUP BY ITH_SER,ITH_ITMCD,MITM_ITMD1
order by ITH_ITMCD ASC
GO
/****** Object:  StoredProcedure [dbo].[sp_getstock_ser_wh]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sp_getstock_ser_wh]
@reffno varchar(45)
as
select ITH_SER, ITH_WH,SUM(ITH_QTY) ITH_QTY,max(ITH_FORM) ITH_FORM from ITH_TBL
WHERE ITH_SER=@reffno
GROUP BY ITH_SER,ITH_WH
having SUM(ITH_QTY)>0
GO
/****** Object:  StoredProcedure [dbo].[sp_getwarehouse_rm]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sp_getwarehouse_rm] as
select MSTLOCG_ID,MSTLOCG_NM from MSTLOCG_TBL where MSTLOCG_NM like '%material%'
GO
/****** Object:  StoredProcedure [dbo].[sp_idpendd_list]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_idpendd_list]
@pwh varchar(20),
@doc varchar(20)
as
--SELECT top 100 b.*,SER_DOC,SER_QTY FROM
--(select ITH_SER, MAX(ITH_LUPDT) ITH_LUPDT from ITH_TBL WHERE ITH_SER IS NOT NULL 
--GROUP BY ITH_SER) a inner join ITH_TBL b on a.ITH_SER=b.ITH_SER AND a.ITH_LUPDT=b.ITH_LUPDT
--INNER JOIN SER_TBL c on b.ITH_SER=c.SER_ID
--WHERE ITH_QTY > 0 AND ITH_WH NOT IN ('ARSHP','QAFG') AND ITH_WH=@pwh and SER_DOC LIKE '%' + @doc + '%' 
--ORDER BY ITH_SER ASC

select b.*,SER_DOC, SER_ITMID ITH_ITMCD from --v1.*,SER_ITMID
(select ITH_WH,ITH_SER,SUM(ITH_QTY) SER_QTY, MAX(ITH_LUPDT) ITH_LUPDT from ITH_TBL WHERE ITH_WH=@pwh
GROUP BY ITH_WH,ITH_SER
HAVING SUM(ITH_QTY) >0) b
left join SER_TBL on ITH_SER=SER_ID
WHERE SER_DOC LIKE '%' + @doc + '%' 
ORDER BY ITH_SER ASC	

GO
/****** Object:  StoredProcedure [dbo].[sp_idpendd_list_byitem]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_idpendd_list_byitem]
@pwh varchar(20),
@pitem varchar(20)
as
--SELECT top 100 b.*,SER_DOC,SER_QTY FROM
--(select ITH_SER, MAX(ITH_LUPDT) ITH_LUPDT from ITH_TBL WHERE ITH_SER IS NOT NULL 
--GROUP BY ITH_SER) a inner join ITH_TBL b on a.ITH_SER=b.ITH_SER AND a.ITH_LUPDT=b.ITH_LUPDT
--INNER JOIN SER_TBL c on b.ITH_SER=c.SER_ID
--WHERE ITH_QTY > 0 AND ITH_WH NOT IN ('ARSHP','QAFG') AND ITH_WH=@pwh and SER_ITMID LIKE '%' + @pitem + '%' 
--ORDER BY ITH_SER ASC


select b.*,SER_DOC, SER_ITMID ITH_ITMCD from --v1.*,SER_ITMID
(select ITH_WH,ITH_SER,SUM(ITH_QTY) SER_QTY, MAX(ITH_LUPDT) ITH_LUPDT from ITH_TBL WHERE ITH_WH=@pwh
GROUP BY ITH_WH,ITH_SER
HAVING SUM(ITH_QTY) >0) b
left join SER_TBL on ITH_SER=SER_ID
WHERE SER_ITMID LIKE '%' + @pitem + '%' 
ORDER BY ITH_SER ASC	

GO
/****** Object:  StoredProcedure [dbo].[sp_idpendd_list_byser]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_idpendd_list_byser]
@pwh varchar(20),
@pser varchar(20)
as
--SELECT top 100 b.*,SER_DOC,SER_QTY FROM
--(select ITH_SER, MAX(ITH_LUPDT) ITH_LUPDT from ITH_TBL WHERE ITH_SER IS NOT NULL 
--GROUP BY ITH_SER) a inner join ITH_TBL b on a.ITH_SER=b.ITH_SER AND a.ITH_LUPDT=b.ITH_LUPDT
--INNER JOIN SER_TBL c on b.ITH_SER=c.SER_ID
--WHERE ITH_QTY > 0 AND ITH_WH NOT IN ('ARSHP','QAFG') AND ITH_WH=@pwh and SER_ID LIKE '%' + @pser + '%' 
--ORDER BY ITH_SER ASC

select b.*,SER_DOC, SER_ITMID ITH_ITMCD from --v1.*,SER_ITMID
(select ITH_WH,ITH_SER,SUM(ITH_QTY) SER_QTY, MAX(ITH_LUPDT) ITH_LUPDT from ITH_TBL WHERE ITH_WH=@pwh
GROUP BY ITH_WH,ITH_SER
HAVING SUM(ITH_QTY) >0) b
left join SER_TBL on ITH_SER=SER_ID
WHERE SER_ID LIKE '%' + @pser + '%' 
ORDER BY ITH_SER ASC	
GO
/****** Object:  StoredProcedure [dbo].[sp_inc_discreapancy]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE procedure [dbo].[sp_inc_discreapancy]
@dono varchar(100)
as
select v0.*, isnull(SCNQTY,0) SCNQTY,MITM_SPTNO from 
(SELECT RCV_DONO,RCV_ITMCD,SUM(RCV_QTY) DOQTY FROM RCV_TBL 
GROUP BY RCV_DONO,RCV_ITMCD ) v0
left join
(select RCVSCN_DONO, RCVSCN_ITMCD, SUM(RCVSCN_QTY) SCNQTY from RCVSCN_TBL
GROUP BY RCVSCN_DONO, RCVSCN_ITMCD) v1 on v0.RCV_DONO=RCVSCN_DONO and RCV_ITMCD=RCVSCN_ITMCD
LEFT JOIN MITM_TBL ON RCV_ITMCD=MITM_ITMCD
WHERE DOQTY!=ISNULL(SCNQTY,0)
and RCV_DONO =@dono
ORDER BY RCV_DONO ASC, RCV_ITMCD ASC
GO
/****** Object:  StoredProcedure [dbo].[sp_inc_discreapancy_h]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE procedure [dbo].[sp_inc_discreapancy_h] as
select RTRIM(RCV_DONO) RCV_DONO,sum(DOQTY) GTDOQTY ,sum(SCNQTY) GTSCNQTY FROM
(select v0.*, isnull(SCNQTY,0) SCNQTY,MITM_SPTNO from 
(SELECT RCV_DONO,RCV_ITMCD,SUM(RCV_QTY) DOQTY FROM RCV_TBL 
GROUP BY RCV_DONO,RCV_ITMCD ) v0
left join
(select RCVSCN_DONO, RCVSCN_ITMCD, SUM(RCVSCN_QTY) SCNQTY from RCVSCN_TBL
GROUP BY RCVSCN_DONO, RCVSCN_ITMCD) v1 on v0.RCV_DONO=RCVSCN_DONO and RCV_ITMCD=RCVSCN_ITMCD
LEFT JOIN MITM_TBL ON RCV_ITMCD=MITM_ITMCD
WHERE DOQTY!=ISNULL(SCNQTY,0)
) vx 
group by RCV_DONO
order by RCV_DONO asc
GO
/****** Object:  StoredProcedure [dbo].[SP_INVOICE_BY_DONO]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SP_INVOICE_BY_DONO]
@dono varchar(45)
as
SELECT V1.*,MITM_ITMD1,DLV_INVNO,DLV_INVDT,MITM_STKUOM FROM
(
SELECT SSO2_MDLCD,SSO2_SLPRC,SUM(SISO_QTY) QTY,DLV_INVNO,DLV_INVDT FROM
(select DISTINCT SISCN_LINENO,DLV_INVNO,DLV_INVDT FROM DLV_TBL 
INNER join SISCN_TBL ON DLV_SER=SISCN_SER
WHERE DLV_ID=@dono
) V0 
INNER JOIN SISO_TBL ON SISCN_LINENO=SISO_HLINE
INNER join XSSO2 ON SISO_CPONO=SSO2_CPONO AND SISO_SOLINE=SSO2_SOLNO
GROUP BY SSO2_MDLCD,SSO2_SLPRC,DLV_INVNO,DLV_INVDT
) V1
LEFT JOIN MITM_TBL ON SSO2_MDLCD=MITM_ITMCD
ORDER BY SSO2_MDLCD ASC


--SELECT V1.*,MITM_ITMD1 FROM
--(select SI_ITMCD,SUM(SISCN_SERQTY) QTY FROM DLV_TBL 
--INNER join SISCN_TBL ON DLV_SER=SISCN_SER
--INNER JOIN SI_TBL ON SISCN_LINENO=SI_LINENO
--WHERE DLV_ID='0002/SMT/IX/2020' 
--GROUP BY SI_ITMCD) V1
--LEFT JOIN MITM_TBL ON SI_ITMCD=MITM_ITMCD
--ORDER BY SI_ITMCD ASC


--select v1.*,count(*) from
--(select SISCN_LINENO from SISCN_TBL 
--where SISCN_DOC='SI202009021'
--group by SISCN_LINENO) v1 left join SISO_TBL on SISCN_LINENO=SISO_HLINE
--group by SISCN_LINENO


--select * from SISO_TBL where SISO_HLINE='SI202009021-5'


GO
/****** Object:  StoredProcedure [dbo].[sp_joblbl_ost]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_joblbl_ost]
as
select PDPP_WONO from
       XWO
        LEFT JOIN
        ( select SER_DOC,SUM(SER_QTY) LBLTTL from SER_TBL x --WHERE SER_DOC LIKE '%%'
        GROUP BY SER_DOC
        ) v2 on PDPP_WONO=v2.SER_DOC
WHERE (PDPP_WORQT-coalesce(LBLTTL,0))>0 and PDPP_WORQT!=PDPP_GRNQT AND PDPP_COMFG='0' --and PDPP_WONO LIKE '%22025%'
AND PDPP_WONO NOT LIKE '%DUMMY%'
ORDER BY PDPP_WONO ASC
GO
/****** Object:  StoredProcedure [dbo].[sp_joblbl_ost_byitem]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[sp_joblbl_ost_byitem]
@itemid varchar(50)
as
select PDPP_WONO from
        (select PDPP_WONO,PDPP_WORQT  from XWO a
        WHERE PDPP_MDLCD LIKE '%'+@itemid+'%'
        ) v1
        LEFT JOIN
        ( select SER_DOC,SUM(SER_QTY) LBLTTL from SER_TBL x WHERE SER_ITMID LIKE '%'+@itemid+'%'
        GROUP BY SER_DOC
        ) v2 on v1.PDPP_WONO=v2.SER_DOC
WHERE (PDPP_WORQT-coalesce(LBLTTL,0))>0
ORDER BY PDPP_WONO ASC


GO
/****** Object:  StoredProcedure [dbo].[sp_kittingstatus_byjob]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_kittingstatus_byjob] @jobno VARCHAR(30)
	,@business VARCHAR(30)
AS
IF (@jobno != '')
BEGIN
	SELECT VMAIN.SPL_DOC
		,CASE 
			WHEN ISNULL(VAL_CHIP, 0) = 0
				AND ISNULL(ROW_CHIP, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_CHIP, 0) > 0
				AND ISNULL(VAL_CHIP, 0) = ISNULL(ROW_CHIP, 0)
				THEN 'O'
			WHEN ISNULL(VAL_CHIP, 0) = 0
				AND ISNULL(ROW_CHIP, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_CHIP, 0) = 0
				AND ISNULL(ROW_CHIP, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_CHIP, 0) > 0
				AND ISNULL(VAL_CHIP, 0) != ISNULL(ROW_CHIP, 0)
				THEN 'T'
			ELSE '-'
			END STSCHIP
		,CASE 
			WHEN ISNULL(VAL_HW, 0) = 0
				AND ISNULL(ROW_HW, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_HW, 0) > 0
				AND ISNULL(VAL_HW, 0) = ISNULL(ROW_HW, 0)
				THEN 'O'
			WHEN ISNULL(VAL_HW, 0) = 0
				AND ISNULL(ROW_HW, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_HW, 0) = 0
				AND ISNULL(ROW_HW, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_HW, 0) > 0
				AND ISNULL(VAL_HW, 0) != ISNULL(ROW_HW, 0)
				THEN 'T'
			ELSE '-'
			END STSHW
		,CASE 
			WHEN ISNULL(VAL_IC, 0) = 0
				AND ISNULL(ROW_IC, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_IC, 0) > 0
				AND ISNULL(VAL_IC, 0) = ISNULL(ROW_IC, 0)
				THEN 'O'
			WHEN ISNULL(VAL_IC, 0) = 0
				AND ISNULL(ROW_IC, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_IC, 0) = 0
				AND ISNULL(ROW_IC, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_IC, 0) > 0
				AND ISNULL(VAL_IC, 0) != ISNULL(ROW_IC, 0)
				THEN 'T'
			END STSIC
		,CASE 
			WHEN ISNULL(VAL_KANBAN, 0) = 0
				AND ISNULL(ROW_KANBAN, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_KANBAN, 0) > 0
				AND ISNULL(VAL_KANBAN, 0) = ISNULL(ROW_KANBAN, 0)
				THEN 'O'
			WHEN ISNULL(VAL_KANBAN, 0) = 0
				AND ISNULL(ROW_KANBAN, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_KANBAN, 0) = 0
				AND ISNULL(ROW_KANBAN, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_KANBAN, 0) > 0
				AND ISNULL(VAL_KANBAN, 0) != ISNULL(ROW_KANBAN, 0)
				THEN 'T'
			ELSE '-'
			END STSKANBAN
		,CASE 
			WHEN ISNULL(VAL_PCB, 0) = 0
				AND ISNULL(ROW_PCB, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_PCB, 0) > 0
				AND ISNULL(VAL_PCB, 0) = ISNULL(ROW_PCB, 0)
				THEN 'O'
			WHEN ISNULL(VAL_PCB, 0) = 0
				AND ISNULL(ROW_PCB, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_PCB, 0) = 0
				AND ISNULL(ROW_PCB, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_PCB, 0) > 0
				AND ISNULL(VAL_PCB, 0) != ISNULL(ROW_PCB, 0)
				THEN 'T'
			ELSE '-'
			END STSPCB
		,CASE 
			WHEN ISNULL(VAL_PREPARE, 0) = 0
				AND ISNULL(ROW_PREPARE, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_PREPARE, 0) > 0
				AND ISNULL(VAL_PREPARE, 0) = ISNULL(ROW_PREPARE, 0)
				THEN 'O'
			WHEN ISNULL(VAL_PREPARE, 0) = 0
				AND ISNULL(ROW_PREPARE, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_PREPARE, 0) = 0
				AND ISNULL(ROW_PREPARE, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_PREPARE, 0) > 0
				AND ISNULL(VAL_PREPARE, 0) != ISNULL(ROW_PREPARE, 0)
				THEN 'T'
			ELSE '-'
			END STSPREPARE
		,CASE 
			WHEN ISNULL(VAL_SP, 0) = 0
				AND ISNULL(ROW_SP, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_SP, 0) > 0
				AND ISNULL(VAL_SP, 0) = ISNULL(ROW_SP, 0)
				THEN 'O'
			WHEN ISNULL(VAL_SP, 0) = 0
				AND ISNULL(ROW_SP, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_SP, 0) = 0
				AND ISNULL(ROW_SP, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_SP, 0) > 0
				AND ISNULL(VAL_SP, 0) != ISNULL(ROW_SP, 0)
				THEN 'T'
			ELSE '-'
			END STSSP
		,REQ_CHIP
		,SUP_CHIP
		,CAST(ACTVAL_CHIP AS DECIMAL(10, 2)) / CAST(REQVAL_CHIP AS DECIMAL(10, 2)) * 100 PRC_CHIP
		,REQ_HW
		,SUP_HW
		,CAST(ACTVAL_HW AS DECIMAL(10, 2)) / CAST(REQVAL_HW AS DECIMAL(10, 2)) * 100 PRC_HW
		,REQ_IC
		,SUP_IC
		,CAST(ACTVAL_IC AS DECIMAL(10, 2)) / CAST(REQVAL_IC AS DECIMAL(10, 2)) * 100 PRC_IC
		,REQ_KANBAN
		,SUP_KANBAN
		,CAST(ACTVAL_KANBAN AS DECIMAL(10, 2)) / CAST(REQVAL_KANBAN AS DECIMAL(10, 2)) * 100 PRC_KANBAN
		,REQ_PCB
		,SUP_PCB
		,CAST(ACTVAL_PCB AS DECIMAL(10, 2)) / CAST(REQVAL_PCB AS DECIMAL(10, 2)) * 100 PRC_PCB
		,REQ_PREPARE
		,SUP_PREPARE
		,CAST(ACTVAL_PREPARE AS DECIMAL(10, 2)) / CAST(REQVAL_PREPARE AS DECIMAL(10, 2)) * 100 PRC_PREPARE
		,REQ_SP
		,SUP_SP
		,CAST(ACTVAL_SP AS DECIMAL(10, 2)) / CAST(REQVAL_SP AS DECIMAL(10, 2)) * 100 PRC_SP
		,LINEDATA_CHIP
		,LINEDATA_HW
		,LINEDATA_IC
		,LINEDATA_KANBAN
		,LINEDATA_PCB
		,LINEDATA_PREPARE
		,LINEDATA_SP
		--,'1' LOGIC
	FROM (
		SELECT VX.SPL_DOC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						THEN _REQ
					END) REQVAL_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						THEN _ACT
					END) ACTVAL_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						THEN LINEDATAREQ
					END) LINEDATA_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						THEN _REQ
					END) REQVAL_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						THEN _ACT
					END) ACTVAL_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						THEN LINEDATAREQ
					END) LINEDATA_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						THEN _REQ
					END) REQVAL_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						THEN _ACT
					END) ACTVAL_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						THEN LINEDATAREQ
					END) LINEDATA_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						THEN _REQ
					END) REQVAL_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						THEN _ACT
					END) ACTVAL_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						THEN LINEDATAREQ
					END) LINEDATA_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						THEN _REQ
					END) REQVAL_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						THEN _ACT
					END) ACTVAL_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						THEN LINEDATAREQ
					END) LINEDATA_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						THEN _REQ
					END) REQVAL_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						THEN _ACT
					END) ACTVAL_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						THEN LINEDATAREQ
					END) LINEDATA_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_SP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						THEN _REQ
					END) REQVAL_SP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						THEN _ACT
					END) ACTVAL_SP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						THEN LINEDATAREQ
					END) LINEDATA_SP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						THEN REQQT
					END) REQ_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						THEN SUPQT
					END) SUP_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						THEN REQQT
					END) REQ_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						THEN SUPQT
					END) SUP_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						THEN REQQT
					END) REQ_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						THEN SUPQT
					END) SUP_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						THEN REQQT
					END) REQ_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						THEN SUPQT
					END) SUP_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						THEN REQQT
					END) REQ_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						THEN SUPQT
					END) SUP_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						THEN REQQT
					END) REQ_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						THEN SUPQT
					END) SUP_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						THEN REQQT
					END) REQ_SP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						THEN SUPQT
					END) SUP_SP
		FROM (
			SELECT V1.*
				,ISNULL(SUPQT, 0) SUPQT
				,1 _REQ
				,CASE 
					WHEN SUPQT >= REQQT
						THEN 1
					ELSE 0
					END _ACT
			FROM (
				SELECT SPL_DOC
					,SPL_LINE
					,SPL_FEDR
					,SPL_ORDERNO
					,SPL_CAT
					,SPL_ITMCD
					,SUM(SPL_QTYREQ) REQQT
					,COUNT(*) LINEDATAREQ
				FROM SPL_TBL
				WHERE SPL_DOC IN (
						SELECT PPSN1_PSNNO
						FROM XPPSN1
						WHERE PPSN1_WONO = @jobno
						GROUP BY PPSN1_PSNNO
						)
				GROUP BY SPL_DOC
					,SPL_LINE
					,SPL_FEDR
					,SPL_ORDERNO
					,SPL_CAT
					,SPL_ITMCD
				) V1
			LEFT JOIN (
				SELECT SPLSCN_DOC
					,SPLSCN_LINE
					,SPLSCN_FEDR
					,SPLSCN_ORDERNO
					,SPLSCN_CAT
					,SPLSCN_ITMCD
					,SUM(SPLSCN_QTY) SUPQT
				FROM SPLSCN_TBL
				GROUP BY SPLSCN_DOC
					,SPLSCN_LINE
					,SPLSCN_FEDR
					,SPLSCN_ORDERNO
					,SPLSCN_CAT
					,SPLSCN_ITMCD
				) V2 ON SPL_DOC = SPLSCN_DOC
				AND SPL_LINE = SPLSCN_LINE
				AND SPL_FEDR = SPLSCN_FEDR
				AND SPL_CAT = SPLSCN_CAT
				AND SPL_ITMCD = SPLSCN_ITMCD
				AND SPL_ORDERNO = SPLSCN_ORDERNO
			) VX
		GROUP BY VX.SPL_DOC
		) VMAIN
	LEFT JOIN (
		SELECT SPL_DOC
			,SUM(CASE 
					WHEN SPL_CAT = 'CHIP'
						THEN 1
					END) ROW_CHIP
			,SUM(CASE 
					WHEN SPL_CAT = 'HW'
						THEN 1
					END) ROW_HW
			,SUM(CASE 
					WHEN SPL_CAT = 'IC'
						THEN 1
					END) ROW_IC
			,SUM(CASE 
					WHEN SPL_CAT = 'KANBAN'
						THEN 1
					END) ROW_KANBAN
			,SUM(CASE 
					WHEN SPL_CAT = 'PCB'
						THEN 1
					END) ROW_PCB
			,SUM(CASE 
					WHEN SPL_CAT = 'PREPARE'
						THEN 1
					END) ROW_PREPARE
			,SUM(CASE 
					WHEN SPL_CAT = 'SP'
						THEN 1
					END) ROW_SP
		FROM (
			SELECT SPL_DOC
				,SPL_CAT
				,SPL_FEDR
				,SPL_ORDERNO
				,SPL_ITMCD
			FROM SPL_TBL
			GROUP BY SPL_DOC
				,SPL_CAT
				,SPL_FEDR
				,SPL_ORDERNO
				,SPL_ITMCD
			) VGRUP
		GROUP BY SPL_DOC
		) VREQ ON VMAIN.SPL_DOC = VREQ.SPL_DOC
	ORDER BY SPL_DOC
END
ELSE
BEGIN
	SELECT VMAIN.SPL_DOC
		,CASE 
			WHEN ISNULL(VAL_CHIP, 0) = 0
				AND ISNULL(ROW_CHIP, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_CHIP, 0) > 0
				AND ISNULL(VAL_CHIP, 0) = ISNULL(ROW_CHIP, 0)
				THEN 'O'
			WHEN ISNULL(VAL_CHIP, 0) = 0
				AND ISNULL(ROW_CHIP, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_CHIP, 0) = 0
				AND ISNULL(ROW_CHIP, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_CHIP, 0) > 0
				AND ISNULL(VAL_CHIP, 0) != ISNULL(ROW_CHIP, 0)
				THEN 'T'
			ELSE '-'
			END STSCHIP
		,CASE 
			WHEN ISNULL(VAL_HW, 0) = 0
				AND ISNULL(ROW_HW, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_HW, 0) > 0
				AND ISNULL(VAL_HW, 0) = ISNULL(ROW_HW, 0)
				THEN 'O'
			WHEN ISNULL(VAL_HW, 0) = 0
				AND ISNULL(ROW_HW, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_HW, 0) = 0
				AND ISNULL(ROW_HW, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_HW, 0) > 0
				AND ISNULL(VAL_HW, 0) != ISNULL(ROW_HW, 0)
				THEN 'T'
			ELSE '-'
			END STSHW
		,CASE 
			WHEN ISNULL(VAL_IC, 0) = 0
				AND ISNULL(ROW_IC, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_IC, 0) > 0
				AND ISNULL(VAL_IC, 0) = ISNULL(ROW_IC, 0)
				THEN 'O'
			WHEN ISNULL(VAL_IC, 0) = 0
				AND ISNULL(ROW_IC, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_IC, 0) = 0
				AND ISNULL(ROW_IC, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_IC, 0) > 0
				AND ISNULL(VAL_IC, 0) != ISNULL(ROW_IC, 0)
				THEN 'T'
			END STSIC
		,CASE 
			WHEN ISNULL(VAL_KANBAN, 0) = 0
				AND ISNULL(ROW_KANBAN, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_KANBAN, 0) > 0
				AND ISNULL(VAL_KANBAN, 0) = ISNULL(ROW_KANBAN, 0)
				THEN 'O'
			WHEN ISNULL(VAL_KANBAN, 0) = 0
				AND ISNULL(ROW_KANBAN, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_KANBAN, 0) = 0
				AND ISNULL(ROW_KANBAN, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_KANBAN, 0) > 0
				AND ISNULL(VAL_KANBAN, 0) != ISNULL(ROW_KANBAN, 0)
				THEN 'T'
			ELSE '-'
			END STSKANBAN
		,CASE 
			WHEN ISNULL(VAL_PCB, 0) = 0
				AND ISNULL(ROW_PCB, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_PCB, 0) > 0
				AND ISNULL(VAL_PCB, 0) = ISNULL(ROW_PCB, 0)
				THEN 'O'
			WHEN ISNULL(VAL_PCB, 0) = 0
				AND ISNULL(ROW_PCB, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_PCB, 0) = 0
				AND ISNULL(ROW_PCB, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_PCB, 0) > 0
				AND ISNULL(VAL_PCB, 0) != ISNULL(ROW_PCB, 0)
				THEN 'T'
			ELSE '-'
			END STSPCB
		,CASE 
			WHEN ISNULL(VAL_PREPARE, 0) = 0
				AND ISNULL(ROW_PREPARE, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_PREPARE, 0) > 0
				AND ISNULL(VAL_PREPARE, 0) = ISNULL(ROW_PREPARE, 0)
				THEN 'O'
			WHEN ISNULL(VAL_PREPARE, 0) = 0
				AND ISNULL(ROW_PREPARE, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_PREPARE, 0) = 0
				AND ISNULL(ROW_PREPARE, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_PREPARE, 0) > 0
				AND ISNULL(VAL_PREPARE, 0) != ISNULL(ROW_PREPARE, 0)
				THEN 'T'
			ELSE '-'
			END STSPREPARE
		,CASE 
			WHEN ISNULL(VAL_SP, 0) = 0
				AND ISNULL(ROW_SP, 0) = 0
				THEN 'O'
			WHEN ISNULL(VAL_SP, 0) > 0
				AND ISNULL(VAL_SP, 0) = ISNULL(ROW_SP, 0)
				THEN 'O'
			WHEN ISNULL(VAL_SP, 0) = 0
				AND ISNULL(ROW_SP, 0) = 1
				THEN 'T'
			WHEN ISNULL(VAL_SP, 0) = 0
				AND ISNULL(ROW_SP, 0) > 0
				THEN 'X'
			WHEN ISNULL(VAL_SP, 0) > 0
				AND ISNULL(VAL_SP, 0) != ISNULL(ROW_SP, 0)
				THEN 'T'
			ELSE '-'
			END STSSP
		,REQ_CHIP
		,SUP_CHIP
		,CAST(ACTVAL_CHIP AS DECIMAL(10, 2)) / CAST(REQVAL_CHIP AS DECIMAL(10, 2)) * 100 PRC_CHIP
		,REQ_HW
		,SUP_HW
		,CAST(ACTVAL_HW AS DECIMAL(10, 2)) / CAST(REQVAL_HW AS DECIMAL(10, 2)) * 100 PRC_HW
		,REQ_IC
		,SUP_IC
		,CAST(ACTVAL_IC AS DECIMAL(10, 2)) / CAST(REQVAL_IC AS DECIMAL(10, 2)) * 100 PRC_IC
		,REQ_KANBAN
		,SUP_KANBAN
		,CAST(ACTVAL_KANBAN AS DECIMAL(10, 2)) / CAST(REQVAL_KANBAN AS DECIMAL(10, 2)) * 100 PRC_KANBAN
		,REQ_PCB
		,SUP_PCB
		,CAST(ACTVAL_PCB AS DECIMAL(10, 2)) / CAST(REQVAL_PCB AS DECIMAL(10, 2)) * 100 PRC_PCB
		,REQ_PREPARE
		,SUP_PREPARE
		,CAST(ACTVAL_PREPARE AS DECIMAL(10, 2)) / CAST(REQVAL_PREPARE AS DECIMAL(10, 2)) * 100 PRC_PREPARE
		,REQ_SP
		,SUP_SP
		,CAST(ACTVAL_SP AS DECIMAL(10, 2)) / CAST(REQVAL_SP AS DECIMAL(10, 2)) * 100 PRC_SP
		,LINEDATA_CHIP
		,LINEDATA_HW
		,LINEDATA_IC
		,LINEDATA_KANBAN
		,LINEDATA_PCB
		,LINEDATA_PREPARE
		,LINEDATA_SP
		--,'2' LOGIC
	FROM (
		SELECT VX.SPL_DOC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						THEN _REQ
					END) REQVAL_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						THEN _ACT
					END) ACTVAL_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						THEN LINEDATAREQ
					END) LINEDATA_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						THEN _REQ
					END) REQVAL_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						THEN _ACT
					END) ACTVAL_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						THEN LINEDATAREQ
					END) LINEDATA_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						THEN _REQ
					END) REQVAL_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						THEN _ACT
					END) ACTVAL_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						THEN LINEDATAREQ
					END) LINEDATA_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						THEN _REQ
					END) REQVAL_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						THEN _ACT
					END) ACTVAL_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						THEN LINEDATAREQ
					END) LINEDATA_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						THEN _REQ
					END) REQVAL_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						THEN _ACT
					END) ACTVAL_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						THEN LINEDATAREQ
					END) LINEDATA_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						THEN _REQ
					END) REQVAL_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						THEN _ACT
					END) ACTVAL_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						THEN LINEDATAREQ
					END) LINEDATA_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						AND REQQT <= SUPQT
						THEN 1
					END) VAL_SP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						THEN _REQ
					END) REQVAL_SP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						THEN _ACT
					END) ACTVAL_SP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						THEN LINEDATAREQ
					END) LINEDATA_SP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						THEN REQQT
					END) REQ_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'CHIP'
						THEN SUPQT
					END) SUP_CHIP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						THEN REQQT
					END) REQ_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'HW'
						THEN SUPQT
					END) SUP_HW
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						THEN REQQT
					END) REQ_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'IC'
						THEN SUPQT
					END) SUP_IC
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						THEN REQQT
					END) REQ_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'KANBAN'
						THEN SUPQT
					END) SUP_KANBAN
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						THEN REQQT
					END) REQ_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PCB'
						THEN SUPQT
					END) SUP_PCB
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						THEN REQQT
					END) REQ_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'PREPARE'
						THEN SUPQT
					END) SUP_PREPARE
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						THEN REQQT
					END) REQ_SP
			,SUM(CASE 
					WHEN VX.SPL_CAT = 'SP'
						THEN SUPQT
					END) SUP_SP
		FROM (
			SELECT V1.*
				,ISNULL(SUPQT, 0) SUPQT
				,1 _REQ
				,CASE 
					WHEN SUPQT >= REQQT
						THEN 1
					ELSE 0
					END _ACT
				,PPSN1_PSNNO
			FROM (
				SELECT SPL_DOC
					,SPL_LINE
					,SPL_FEDR
					,SPL_ORDERNO
					,SPL_CAT
					,SPL_ITMCD
					,SUM(SPL_QTYREQ) REQQT
					,COUNT(*) LINEDATAREQ
				FROM SPL_TBL
				WHERE SPL_BG=@business AND SUBSTRING(SPL_DOC,8,4) IN (YEAR(GETDATE()), YEAR(GETDATE())-1)
				GROUP BY SPL_DOC
					,SPL_LINE
					,SPL_FEDR
					,SPL_ORDERNO
					,SPL_CAT
					,SPL_ITMCD
				) V1
			LEFT JOIN (
				SELECT SPLSCN_DOC
					,SPLSCN_LINE
					,SPLSCN_FEDR
					,SPLSCN_ORDERNO
					,SPLSCN_CAT
					,SPLSCN_ITMCD
					,SUM(SPLSCN_QTY) SUPQT
				FROM SPLSCN_TBL
				WHERE SUBSTRING(SPLSCN_DOC,8,4) IN (YEAR(GETDATE()), YEAR(GETDATE())-1)
				GROUP BY SPLSCN_DOC
					,SPLSCN_LINE
					,SPLSCN_FEDR
					,SPLSCN_ORDERNO
					,SPLSCN_CAT
					,SPLSCN_ITMCD
				) V2 ON SPL_DOC = SPLSCN_DOC
				AND SPL_LINE = SPLSCN_LINE
				AND SPL_FEDR = SPLSCN_FEDR
				AND SPL_CAT = SPLSCN_CAT
				AND SPL_ITMCD = SPLSCN_ITMCD
				AND SPL_ORDERNO = SPLSCN_ORDERNO
			LEFT JOIN OPENQUERY([SRVMEGA], 'SELECT PPSN1_PSNNO,PPSN1_DOCNO FROM PSI_MEGAEMS.dbo.PPSN1_TBL WHERE PPSN1_DOCNO!='''' GROUP BY PPSN1_PSNNO,PPSN1_DOCNO') A
				ON SPL_DOC=PPSN1_PSNNO
			) VX
			
			WHERE PPSN1_PSNNO!=''
			--WHERE REQQT>ISNULL(SUPQT,0) AND PPSN1_PSNNO!=''
		GROUP BY VX.SPL_DOC
		) VMAIN
	LEFT JOIN (
		SELECT SPL_DOC
			,SUM(CASE 
					WHEN SPL_CAT = 'CHIP'
						THEN 1
					END) ROW_CHIP
			,SUM(CASE 
					WHEN SPL_CAT = 'HW'
						THEN 1
					END) ROW_HW
			,SUM(CASE 
					WHEN SPL_CAT = 'IC'
						THEN 1
					END) ROW_IC
			,SUM(CASE 
					WHEN SPL_CAT = 'KANBAN'
						THEN 1
					END) ROW_KANBAN
			,SUM(CASE 
					WHEN SPL_CAT = 'PCB'
						THEN 1
					END) ROW_PCB
			,SUM(CASE 
					WHEN SPL_CAT = 'PREPARE'
						THEN 1
					END) ROW_PREPARE
			,SUM(CASE 
					WHEN SPL_CAT = 'SP'
						THEN 1
					END) ROW_SP
		FROM (
			SELECT SPL_DOC
				,SPL_CAT
				,SPL_FEDR
				,SPL_ORDERNO
				,SPL_ITMCD
			FROM SPL_TBL
			WHERE SPL_BG=@business AND SUBSTRING(SPL_DOC,8,4) IN (YEAR(GETDATE()), YEAR(GETDATE())-1)
			GROUP BY SPL_DOC
				,SPL_CAT
				,SPL_FEDR
				,SPL_ORDERNO
				,SPL_ITMCD
			) VGRUP
		GROUP BY SPL_DOC
		) VREQ ON VMAIN.SPL_DOC = VREQ.SPL_DOC
	WHERE
		CAST(ACTVAL_CHIP AS DECIMAL(10, 2)) / CAST(REQVAL_CHIP AS DECIMAL(10, 2)) * 100 != 100
		OR CAST(ACTVAL_HW AS DECIMAL(10, 2)) / CAST(REQVAL_HW AS DECIMAL(10, 2)) * 100 != 100
		OR CAST(ACTVAL_IC AS DECIMAL(10, 2)) / CAST(REQVAL_IC AS DECIMAL(10, 2)) * 100 != 100		
		OR CAST(ACTVAL_KANBAN AS DECIMAL(10, 2)) / CAST(REQVAL_KANBAN AS DECIMAL(10, 2)) * 100 !=100		
		OR CAST(ACTVAL_PCB AS DECIMAL(10, 2)) / CAST(REQVAL_PCB AS DECIMAL(10, 2)) * 100 !=100
		OR CAST(ACTVAL_PREPARE AS DECIMAL(10, 2)) / CAST(REQVAL_PREPARE AS DECIMAL(10, 2)) * 100 !=100		
		OR CAST(ACTVAL_SP AS DECIMAL(10, 2)) / CAST(REQVAL_SP AS DECIMAL(10, 2)) * 100 != 100
	ORDER BY SPL_DOC
END
GO
/****** Object:  StoredProcedure [dbo].[sp_lastser]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_lastser]  
@typemodel varchar(1),
@prodt varchar(10)
AS    
 
	SET NOCOUNT ON;  
	--select TOP 1 convert(bigint,SUBSTRING(SER_ID,7,10)) lser from SER_TBL 
	--WHERE
	--substring(SER_ID, 1,1) =@typemodel
	--AND SER_PRDDT=@prodt AND SER_SHEET is not null
	--order by SER_LUPDT DESC, 1 DESC

	select max(convert(bigint,SUBSTRING(SER_ID,7,10))) lser from SER_TBL 
	WHERE
	substring(SER_ID, 1,1) =@typemodel
	AND SER_PRDDT=@prodt AND SER_SHEET is not null
	
   
RETURN  

GO
/****** Object:  StoredProcedure [dbo].[sp_lastser_ihour]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_lastser_ihour]  
@typemodel varchar(1),
@prodt varchar(10)
AS    
 
	SET NOCOUNT ON;  
	
	select max(convert(bigint,SUBSTRING(SER_ID,9,10))) lser from SER_TBL 
	WHERE
	substring(SER_ID, 1,1) =@typemodel
	AND SER_PRDDT=@prodt AND SER_SHEET is not null
GO
/****** Object:  StoredProcedure [dbo].[sp_lastser_wip]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_lastser_wip]  
@typemodel varchar(1),
@prodt varchar(10)
AS    
 
	SET NOCOUNT ON;  

	select max(convert(bigint,SUBSTRING(SER_ID,7,10))) lser from SER_WIP_TBL 
	WHERE
	substring(SER_ID, 1,1) =@typemodel
	AND SER_PRDDT=@prodt AND SER_SHEET is not null
	
   
RETURN  
GO
/****** Object:  StoredProcedure [dbo].[sp_laststatus_ser]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sp_laststatus_ser]
@reffno varchar(50)
as
select x1.* from
(SELECT v1.*,a.ITH_LOC,ITH_FORM,ITH_DATE,isnull(ITH_LINE,'') ITH_LINE from
		(select ITH_SER,SUM(ITH_QTY) ITH_QTY,max(ITH_LUPDT) ITH_LUPDT,ITH_WH
		from ITH_TBL WHERE ITH_SER=@reffno
		GROUP BY ITH_SER, ITH_WH
		HAVING SUM(ITH_QTY) >0
		) v1
		INNER JOIN ITH_TBL a ON  a.ITH_WH=v1.ITH_WH and isnull(v1.ITH_LUPDT,getdate())=isnull(a.ITH_LUPDT,getdate()) and v1.ITH_SER=a.ITH_SER) x1
INNER JOIN 
(	
select ITH_SER,isnull(MAX(ITH_LINE),'') VNX_MAXLINE from(
SELECT  v1.*,ITH_LOC,ITH_LINE from
(select ITH_WH,ITH_SER,SUM(ITH_QTY) ITH_QTY, MAX(ITH_LUPDT) LTS_TIME from ITH_TBL 
GROUP BY ITH_WH,ITH_SER
HAVING SUM(ITH_QTY) >0) v1
inner join ITH_TBL a ON v1.ITH_SER=a.ITH_SER and a.ITH_WH=v1.ITH_WH and isnull(v1.LTS_TIME,getdate())=isnull(a.ITH_LUPDT,getdate()) --AND v1.LTS_LINE=ISNULL(ITH_LINE,'')
) vn
group by ITH_SER) x2 on x1.ITH_SER=x2.ITH_SER and x1.ITH_LINE=x2.VNX_MAXLINE
GO
/****** Object:  StoredProcedure [dbo].[sp_lotsize_vs_psn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_lotsize_vs_psn]
@WO VARCHAR(40)
AS
select XWO.*,PPSN2_SUBPN,PPSN2_ACTQT from XWO left join XPPSN1
on XWO.PDPP_WONO=PPSN1_WONO
LEFT JOIN XPPSN2 ON 
	PPSN1_DOCNO=PPSN2_DOCNO 
	AND PPSN1_PSNNO=PPSN2_PSNNO
	AND PPSN1_FR=PPSN2_FR
	and PPSN1_PROCD=PPSN2_PROCD
LEFT JOIN (SELECT PIS3_WONO,PIS3_FR,PIS3_MC,PIS3_ITMCD,PIS3_MCZ,PIS3_DOCNO,PIS3_PROCD,PIS3_LINENO FROM XPIS3
	where PIS3_WONO=@WO
	GROUP BY PIS3_WONO,PIS3_FR,PIS3_MC,PIS3_ITMCD,PIS3_MCZ,PIS3_DOCNO,PIS3_PROCD,PIS3_LINENO) V ON 
	PPSN1_WONO=PIS3_WONO
	AND PPSN2_FR=PIS3_FR
	AND PPSN2_MC=PIS3_MC
	AND PPSN2_SUBPN=PIS3_ITMCD
	and PPSN2_MCZ=PIS3_MCZ
	and PPSN2_DOCNO=PIS3_DOCNO	
	and PPSN2_PROCD=PIS3_PROCD
	and PPSN2_LINENO=PIS3_LINENO
WHERE PPSN2_ITMCAT='PCB' AND PDPP_WORQT>PPSN2_ACTQT
AND PDPP_WONO = @WO
and PIS3_ITMCD is not null
and PDPP_BSGRP='PSI1PPZIEP'
and PPSN1_PROCD!='SMT-SP'
order by PDPP_WONO
GO
/****** Object:  StoredProcedure [dbo].[SP_PACKINGLIST_BY_DONO]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SP_PACKINGLIST_BY_DONO]
@dono varchar(45)
as
SELECT V1.*,RTRIM(MITM_ITMD1) MITM_ITMD1, RTRIM(MITM_ITMD2) MITM_ITMD2,(MITM_NWG * (SISCN_SERQTY * TTLBOX)) MITM_NWG, (MITM_NWG * (SISCN_SERQTY * TTLBOX)) + (TTLBOX*MITM_BOXWEIGHT) MITM_GWG, 0 TTLBARIS , TTLDLV, MITM_NWG MSTNWG FROM
(
select SI_ITMCD,SISCN_SERQTY, count(*) TTLBOX  FROM DLV_TBL 
INNER join SISCN_TBL ON DLV_SER=SISCN_SER
INNER join SI_TBL ON SISCN_LINENO=SI_LINENO
WHERE DLV_ID=@dono
GROUP BY SI_ITMCD,SISCN_SERQTY
) V1
LEFT JOIN MITM_TBL ON SI_ITMCD=MITM_ITMCD
left join (
 SELECT SER_ITMID,SUM(DLV_QTY) TTLDLV  FROM DLV_TBL
 LEFT JOIN SER_TBL ON DLV_SER=SER_ID
 WHERE DLV_ID=@dono
 GROUP BY SER_ITMID
) V2 ON  V1.SI_ITMCD=V2.SER_ITMID
ORDER BY SI_ITMCD ASC
GO
/****** Object:  StoredProcedure [dbo].[sp_ppsn2_xwo]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_ppsn2_xwo]
@wo varchar(50)
as

--- CRATED BY : ANA
--- CREATED DATE : 2022-10-10

select PPSN1_WONO,LBLTTL,SUM(PPSN2_ACTQT) MGQT
,CONVERT(BIGINT, (
			(
				 SUM(PPSN2_ACTQT)
					
				) - coalesce(LBLTTL, 0)
			)) OSTSUPQTY

from
(
SELECT PPSN1_PSNNO
		,PPSN1_WONO
	FROM XPPSN1
	WHERE PPSN1_WONO = @wo
	GROUP BY PPSN1_PSNNO
		,PPSN1_WONO
	) VPSN
LEFT JOIN XPPSN2 ON PPSN1_PSNNO = PPSN2_PSNNO
LEFT JOIN XWO ON PPSN1_WONO = PDPP_WONO
LEFT JOIN (
	SELECT SER_DOC
		,SUM(SER_QTYLOT) LBLTTL
	FROM SER_TBL x
	WHERE SER_DOC = @wo
	GROUP BY SER_DOC
	) v2 ON PDPP_WONO = SER_DOC

WHERE PPSN2_ITMCAT = 'PCB'
AND PPSN2_PROCD != 'SMT-SP'
group by PPSN1_WONO,LBLTTL
GO
/****** Object:  StoredProcedure [dbo].[sp_psistock]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_psistock] 
@pwh varchar(15),
@pitem  varchar(50),
@pbg varchar(120)
as
select ITH_WH,ITH_ITMCD,MITM_ITMD1,MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,MITM_STKUOM from ITH_TBL a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
left join v_mitm_bsgroup on ITH_ITMCD=PDPP_MDLCD
WHERE ITH_WH=@pwh AND ITH_ITMCD like '' + @pitem + '%' and PDPP_BSGRP in (SELECT CONVERT(VARCHAR,VALUE) FROM string_split(@pbg,',') )
AND ITH_FORM NOT IN ('SASTART','SA')
GROUP BY ITH_ITMCD,ITH_WH,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1
ORDER BY ITH_ITMCD ASC
GO
/****** Object:  StoredProcedure [dbo].[sp_psnno_list]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_psnno_list]
@psnno varchar(19)
as
select SPL_DOC from SPL_TBL WHERE SPL_DOC LIKE CONCAT('%',@psnno,'%%')
GROUP BY SPL_DOC 
ORDER BY SPL_DOC ASC
GO
/****** Object:  StoredProcedure [dbo].[sp_qcwh_unscan]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[sp_qcwh_unscan] as
SELECT v1.*
	,SER_ITMID ITH_ITMCD
	,MITM_ITMD1
	,SER_LOTNO
	,CONCAT (
		MSTEMP_FNM
		,' '
		,MSTEMP_LNM
		) PIC
	,SER_BSGRP PDPP_BSGRP
	,SER_RMRK
FROM (
	SELECT ITH_SER
		,SUM(ITH_QTY) ITH_QTY
		,max(ITH_LUPDT) ITH_LUPDT
		,MAX(ITH_USRID) ITH_USRID
		,MAX(ITH_DOC) ITH_DOC
	FROM ITH_TBL
	WHERE ITH_WH = 'ARQA1'
	GROUP BY ITH_SER
	HAVING SUM(ITH_QTY) > 0
	) v1
LEFT JOIN SER_TBL ON v1.ITH_SER = SER_ID
LEFT JOIN MSTEMP_TBL ON ITH_USRID = MSTEMP_ID
LEFT JOIN MITM_TBL ON SER_ITMID = MITM_ITMCD
WHERE DATEDIFF(DAY, getdate(), ITH_LUPDT) >= -365 AND ISNULL(SER_RMRK,'') NOT LIKE '%SCR%'
ORDER BY v1.ITH_LUPDT DESC
GO
/****** Object:  StoredProcedure [dbo].[sp_qcwh_unscan_recap]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_qcwh_unscan_recap] as
SELECT ITH_ITMCD,COUNT(*) TTLBOX  from --v1.*,ITH_ITMCD,ITH_DOC
(select ITH_SER,SUM(ITH_QTY) ITH_QTY, max(ITH_LUPDT) ITH_LUPDT from ITH_TBL 
WHERE ITH_WH='ARQA1'
GROUP BY ITH_SER
HAVING SUM(ITH_QTY)>0
) v1 
 left JOIN (
 select ITH_ITMCD,ITH_SER from ITH_TBL 
WHERE ITH_WH='ARQA1'
 ) v2 on v1.ITH_SER=v2.ITH_SER
 GROUP BY ITH_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[sp_qcwh_unscan_recap_lastscan]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_qcwh_unscan_recap_lastscan] AS
select vlastscan.*, TTLBOX from
(SELECT ITH_ITMCD,ITH_LOC, PPCLASTSCAN FROM ITH_TBL RIGHT JOIN
(SELECT max(ITH_LUPDT) PPCLASTSCAN
		FROM ITH_TBL 
		WHERE ITH_WH='AFWH3' 
		 AND ITH_FORM='INC-WH-FG'
		 AND ITH_DATE>=DATEADD(MONTH,-5,GETDATE())
		 ) vlastscan
ON ITH_LUPDT=PPCLASTSCAN
AND ITH_QTY>0) vlastscan
inner join (
	SELECT ITH_ITMCD,COUNT(*) TTLBOX  from --v1.*,ITH_ITMCD,ITH_DOC
	(select ITH_SER,SUM(ITH_QTY) ITH_QTY, max(ITH_LUPDT) ITH_LUPDT from ITH_TBL 
	WHERE ITH_WH='ARQA1'
	GROUP BY ITH_SER
	HAVING SUM(ITH_QTY)>0
	) v1 
	 INNER JOIN (
		 select * from ITH_TBL 
		WHERE ITH_WH='ARQA1'
	 ) v2 on v1.ITH_SER=v2.ITH_SER
	 GROUP BY ITH_ITMCD
) vrecap on vlastscan.ITH_ITMCD= vrecap.ITH_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[sp_report_inc_pab]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_report_inc_pab] @doctype VARCHAR(5)
	,@tpbtype VARCHAR(5)
	,@itmcd VARCHAR(50)
	,@supplier VARCHAR(15)
	,@date0 VARCHAR(10)
	,@date1 VARCHAR(10)
	,@noaju VARCHAR(26)
	,@tujuan VARCHAR(5)
	,@itmType VARCHAR(5)
AS
BEGIN
	IF (@noaju != '')
	BEGIN
		SELECT RCV_RPNO
			,RCV_RPDATE
			,RCV_RCVDATE
			,RCV_ITMCD
			,coalesce(MITM_ITMD1, '') MITM_ITMD1
			,RCV_HSCD
			,SUM(RCV_QTY) RCV_QTY
			,MITM_STKUOM
			,TTLAMOUNT RCV_TTLAMT
			,RCV_NW
			,MSUP_SUPNM
			,MSUP_ADDR1
			,RCV_DONO
			,RCV_BCNO
			,URAIAN_TUJUAN_PENGIRIMAN
			,RCV_INVNO
			,RCV_PRPRC
			,MSUP_CURCD
			,MAX(RCV_BM) RCV_BM
			,MAX(RCV_PPN) RCV_PPN
			,MAX(RCV_PPH) RCV_PPH
		FROM RCV_TBL a
		LEFT JOIN MITM_TBL b ON a.RCV_ITMCD = b.MITM_ITMCD
		LEFT JOIN (
			SELECT MSUP_SUPCD
				,max(MSUP_SUPNM) MSUP_SUPNM
				,max(MSUP_ADDR1) MSUP_ADDR1
				,MAX(MSUP_SUPCR) MSUP_CURCD
			FROM (
				SELECT MSUP_SUPCD
					,MSUP_SUPNM
					,MSUP_ADDR1
					,MSUP_SUPCR
				FROM MSUP_TBL
				
				UNION
				
				SELECT MCUS_CUSCD MSUP_SUPCD
					,MCUS_CUSNM MSUP_SUPNM
					,MCUS_ADDR1 MSUP_ADDR1
					,MCUS_CURCD MSUP_SUPCR
				FROM XMCUS
				) vb
			GROUP BY MSUP_SUPCD
			) c ON a.RCV_SUPCD = c.MSUP_SUPCD
		LEFT JOIN ZTJNKIR_TBL ON RCV_ZSTSRCV = ZTJNKIR_TBL.KODE_TUJUAN_PENGIRIMAN
			AND RCV_BCTYPE = ZTJNKIR_TBL.KODE_DOKUMEN
		LEFT JOIN (
			SELECT HRPNO
				,HDO
				,SUM(AMOUNT) TTLAMOUNT
			FROM (
				SELECT RCV_RPNO HRPNO
					,RCV_DONO HDO
					,SUM(RCV_QTY) RQT
					,RCV_PRPRC
					,SUM(RCV_QTY) * RCV_PRPRC AMOUNT
				FROM RCV_TBL
				WHERE RCV_QTY > 0
				GROUP BY RCV_RPNO
					,RCV_DONO
					,RCV_PRPRC
				) V1
			GROUP BY HRPNO
				,HDO
			) V2 ON RCV_RPNO = HRPNO
			AND RCV_DONO = HDO
		WHERE RCV_RPNO LIKE CONCAT (
				'%'
				,@noaju
				,'%'
				)
		GROUP BY RCV_RPNO
			,RCV_RPDATE
			,RCV_RCVDATE
			,RCV_ITMCD
			,MITM_ITMD1
			,RCV_HSCD
			,MITM_STKUOM
			,MSUP_SUPNM
			,RCV_TTLAMT
			,RCV_NW
			,MSUP_ADDR1
			,RCV_DONO
			,RCV_BCNO
			,URAIAN_TUJUAN_PENGIRIMAN
			,RCV_INVNO
			,TTLAMOUNT
			,RCV_PRPRC
			,MSUP_CURCD
		HAVING sum(RCV_QTY) > 0
		ORDER BY RCV_RPDATE ASC
			,RCV_RPNO ASC
			,RCV_DONO ASC
			,RCV_ITMCD ASC
	END
	ELSE
	BEGIN
		SELECT RCV_RPNO
			,RCV_RPDATE
			,RCV_RCVDATE
			,RCV_ITMCD
			,coalesce(MITM_ITMD1, '') MITM_ITMD1
			,RCV_HSCD
			,SUM(RCV_QTY) RCV_QTY
			,MITM_STKUOM
			,TTLAMOUNT RCV_TTLAMT
			,RCV_NW
			,MSUP_SUPNM
			,MSUP_ADDR1
			,RCV_DONO
			,RCV_BCNO
			,URAIAN_TUJUAN_PENGIRIMAN
			,RCV_INVNO
			,RCV_PRPRC
			,RTRIM(MSUP_CURCD) MSUP_CURCD
			,MAX(RCV_BM) RCV_BM
			,MAX(RCV_PPN) RCV_PPN
			,MAX(RCV_PPH) RCV_PPH
		FROM RCV_TBL a
		LEFT JOIN MITM_TBL b ON a.RCV_ITMCD = b.MITM_ITMCD
		LEFT JOIN (
			SELECT MSUP_SUPCD
				,max(MSUP_SUPNM) MSUP_SUPNM
				,max(MSUP_ADDR1) MSUP_ADDR1
				,MAX(MSUP_SUPCR) MSUP_CURCD
			FROM (
				SELECT MSUP_SUPCD
					,MSUP_SUPNM
					,MSUP_ADDR1
					,MSUP_SUPCR
				FROM MSUP_TBL
				
				UNION
				
				SELECT MCUS_CUSCD MSUP_SUPCD
					,MCUS_CUSNM MSUP_SUPNM
					,MCUS_ADDR1 MSUP_ADDR1
					,MCUS_CURCD MSUP_SUPCR
				FROM XMCUS
				) vb
			GROUP BY MSUP_SUPCD
			) c ON a.RCV_SUPCD = c.MSUP_SUPCD
		LEFT JOIN ZTJNKIR_TBL ON RCV_ZSTSRCV = ZTJNKIR_TBL.KODE_TUJUAN_PENGIRIMAN
			AND RCV_BCTYPE = ZTJNKIR_TBL.KODE_DOKUMEN
		LEFT JOIN (
			SELECT HRPNO
				,HDO
				,SUM(AMOUNT) TTLAMOUNT
			FROM (
				SELECT RCV_RPNO HRPNO
					,RCV_DONO HDO
					,SUM(RCV_QTY) RQT
					,RCV_PRPRC
					,SUM(RCV_QTY) * RCV_PRPRC AMOUNT
				FROM RCV_TBL
				WHERE RCV_QTY > 0
				GROUP BY RCV_RPNO
					,RCV_DONO
					,RCV_PRPRC
				) V1
			GROUP BY HRPNO
				,HDO
			) V2 ON RCV_RPNO = HRPNO
			AND RCV_DONO = HDO
		WHERE (
				RCV_RPDATE BETWEEN @date0
					AND @date1
				)
			AND RCV_BCTYPE LIKE '%' + @doctype + '%'
			AND MITM_MODEL LIKE '%' + @itmType + '%'
			AND ISNULL(RCV_ZSTSRCV, '') LIKE '%' + @tujuan + '%'
			AND RCV_TPB LIKE '%' + @tpbtype + '%'
		GROUP BY RCV_RPNO
			,RCV_RPDATE
			,RCV_RCVDATE
			,RCV_ITMCD
			,MITM_ITMD1
			,RCV_HSCD
			,MITM_STKUOM
			,MSUP_SUPNM
			,RCV_TTLAMT
			,RCV_NW
			,MSUP_ADDR1
			,RCV_DONO
			,RCV_BCNO
			,URAIAN_TUJUAN_PENGIRIMAN
			,RCV_INVNO
			,TTLAMOUNT
			,RCV_PRPRC
			,MSUP_CURCD
		HAVING sum(RCV_QTY) > 0
		ORDER BY RCV_RPDATE ASC
			,RCV_RPNO ASC
			,RCV_DONO ASC
			,RCV_ITMCD ASC
	END
END
GO
/****** Object:  StoredProcedure [dbo].[sp_rincoming_fg]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_rincoming_fg]
@jobno varchar(50),
@sts varchar(50),
@pbg varchar(20)
as
if (@sts='1' and @jobno is not null ) 
	begin
		SELECT RTRIM(PDPP_WONO) SER_DOC, PDPP_MDLCD SER_ITMID, RTRIM(MITM_ITMD1) MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,
		SUM(case when  ITH_WH='ARPRD1' AND ITH_FORM= 'INC-PRD-FG' AND ISNULL(SER_CAT,'')!='2' THEN ITH_QTY
		END) QTY_PRD		
		,sum(case when  ITH_WH='ARQA1' and ITH_FORM='INC-QA-FG' AND SER_REFNO=ITH_SER THEN ISNULL(SER_QTYLOT,ITH_QTY)
		END) QTY_QA		
		,sum(case when  ITH_WH='AFWH3' AND ITH_FORM='INC-WH-FG' AND SER_REFNO=ITH_SER  THEN ITH_QTY
		END) QTY_WH
		,PDPP_BSGRP		
		,sum(case when ITH_WH IN ('ARPRD1','ARQA1') AND ITH_FORM IN ('INC-PRD-FG') AND SER_CAT='2' AND SER_RMRK!='SCRAP' THEN ITH_QTY
		END) NG
		,ISNULL(MAX(SCRQTY),0) SCRQTY
		,sum(case when  ITH_WH='ARPRD1' AND SER_CAT='2' AND SER_RMRK='SCRAP' AND ITH_FORM='INC-PRD-FG' THEN ITH_QTY
		END) QTY_SCR
		,PDPP_BOMRV
		,PDPP_COMFG
		FROM ITH_TBL a 
		RIGHT JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		RIGHT JOIN (select PDPP_WONO,PDPP_WORQT,PDPP_MDLCD,PDPP_GRNQT,PDPP_COMFG,PDPP_BSGRP,PDPP_BOMRV from XWO where PDPP_BSGRP=@pbg  ) v1 ON c.SER_DOC=v1.PDPP_WONO --1and SER_ITMID=PDPP_MDLCD
		LEFT join MITM_TBL b on PDPP_MDLCD=b.MITM_ITMCD
		LEFT JOIN (SELECT DOC_NO,SUM(QTY) SCRQTY FROM ZRPSCRAP_HIST LEFT JOIN (SELECT ITH_SER FROM ITH_TBL WHERE ITH_SER IS NOT NULL AND ITH_WH in ('AFWH3','ARPRD1') GROUP BY ITH_SER HAVING SUM(ITH_QTY) >0) VFG ON REF_NO=ITH_SER WHERE IS_DONE='1' GROUP BY DOC_NO) AS VSCR ON PDPP_WONO=DOC_NO
		LEFT JOIN (SELECT ITH_SER CONVERTSER FROM ITH_TBL WHERE ITH_WH='ARPRD1' AND ITH_REMARK='CONVERT') VCONVERT ON ITH_SER=CONVERTSER		
		where ( ISNULL(MITM_MODEL,'') ='1' AND PDPP_WONO like '%'+@jobno+'%'	
		and ISNULL(ITH_FORM,'') IN ('INC-PRD-FG', 'INC-QA-FG','INC-WH-FG') --1AND CONVERTSER IS NULL
		) OR
		( ISNULL(MITM_MODEL,'') ='1' AND PDPP_WONO like '%'+@jobno+'%'	
		--1AND CONVERTSER IS NULL
		) 		
		GROUP BY PDPP_WONO,PDPP_MDLCD, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,PDPP_COMFG,PDPP_BSGRP,PDPP_BOMRV
		having ISNULL(SUM(case when  ITH_WH='ARPRD1' AND ITH_FORM= 'INC-PRD-FG' THEN ITH_QTY --SER_QTY
		END),0)>=PDPP_WORQT OR PDPP_WORQT=PDPP_GRNQT OR PDPP_COMFG='1'
		ORDER BY SUBSTRING(PDPP_WONO,1,7) DESC, SER_ITMID
	end
else if (@sts ='0' and @jobno is not null)
	begin
		SELECT RTRIM(PDPP_WONO) SER_DOC,PDPP_MDLCD SER_ITMID, RTRIM(MITM_ITMD1) MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,
		SUM(case when  ITH_WH='ARPRD1' AND ITH_FORM= 'INC-PRD-FG' AND ISNULL(SER_CAT,'')!='2' THEN ITH_QTY
		END) QTY_PRD
		,sum(case when  ITH_WH='ARQA1' and ITH_FORM='INC-QA-FG' AND SER_REFNO=ITH_SER THEN ISNULL(SER_QTYLOT,ITH_QTY)
		END) QTY_QA
		,sum(case when  ITH_WH='AFWH3' AND ITH_FORM='INC-WH-FG' AND SER_REFNO=ITH_SER  THEN ITH_QTY
		END) QTY_WH
		,PDPP_BSGRP		
		,sum(case when ITH_WH IN ('ARPRD1','ARQA1') AND SER_CAT='2' AND SER_RMRK!='SCRAP' THEN ITH_QTY
		END) NG
		,ISNULL(MAX(SCRQTY),0) SCRQTY
		,sum(case when ITH_WH IN ('ARPRD1','ARQA1') AND SER_CAT='2' AND SER_RMRK='SCRAP' THEN ITH_QTY END) QTY_SCR
		,PDPP_BOMRV
		,PDPP_COMFG
		
		FROM ITH_TBL a 
		RIGHT JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		RIGHT JOIN (select PDPP_WONO,PDPP_WORQT,PDPP_MDLCD,PDPP_GRNQT,PDPP_BSGRP,PDPP_BOMRV,PDPP_COMFG from XWO WHERE PDPP_COMFG='0' and PDPP_BSGRP=@pbg ) v1 ON c.SER_DOC=v1.PDPP_WONO --and SER_ITMID=PDPP_MDLCD
		LEFT JOIN MITM_TBL b on PDPP_MDLCD=b.MITM_ITMCD
		LEFT JOIN (SELECT DOC_NO,SUM(QTY) SCRQTY FROM ZRPSCRAP_HIST 
					LEFT JOIN (SELECT ITH_SER FROM ITH_TBL WHERE ITH_SER IS NOT NULL AND ITH_WH in ('AFWH3','ARPRD1') 
								GROUP BY ITH_SER HAVING SUM(ITH_QTY) >0) VFG 
						ON REF_NO=ITH_SER WHERE IS_DONE='1' 
					GROUP BY DOC_NO) AS VSCR 
			ON PDPP_WONO=DOC_NO
		
		WHERE (MITM_MODEL ='1' AND (ITH_QTY !=0  OR ITH_QTY IS NULL)
		AND PDPP_WONO like '%'+@jobno+'%'
		and (ITH_FORM IN ('INC-PRD-FG', 'INC-QA-FG','INC-WH-FG','FG-BGN','OUT-PRD-FG','OUT-QA-FG') OR ITH_FORM IS NULL))
		and PDPP_WORQT!=PDPP_GRNQT
		GROUP BY PDPP_WONO,PDPP_MDLCD, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,PDPP_BSGRP,PDPP_BOMRV,PDPP_COMFG
		
		HAVING ISNULL(SUM(case when ITH_WH='ARPRD1' AND ITH_FORM= 'INC-PRD-FG' AND ISNULL(SER_CAT,'')!='2' THEN ITH_QTY
		END),0)+
		ISNULL(sum(case when ITH_WH IN ('ARPRD1','ARQA1') AND SER_CAT='2' AND SER_RMRK!='SCRAP' THEN ITH_QTY
		END),0)+
		isnull(sum(case when ITH_WH IN ('ARPRD1','ARQA1') AND SER_CAT='2' AND SER_RMRK='SCRAP' THEN ITH_QTY END),0)<PDPP_WORQT
		
		ORDER BY SUBSTRING(PDPP_WONO,1,7) DESC, SER_ITMID
	end
GO
/****** Object:  StoredProcedure [dbo].[sp_rincoming_fg_mega]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[sp_rincoming_fg_mega]
@jobno varchar(50),
@dt varchar(25),
@dt2 varchar(25)
as
if (@dt is null and @jobno is not null ) 
	begin
		SELECT SER_DOC,SER_ITMID
		,sum(SER_QTY) QTY_WH
		FROM ITH_TBL a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
		INNER JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		where MITM_MODEL ='1' AND ITH_QTY >0 AND ITH_WH='AFWH3' AND SER_DOC like '%' + @jobno + '%'
		and ITH_FORM='INC-WH-FG'
		GROUP BY SER_DOC,SER_ITMID
		ORDER BY  SER_DOC ASC		
	end
else if (@dt is not null and @jobno is not null)
	begin	
		SELECT SER_DOC,SER_ITMID
		,sum(SER_QTY) QTY_WH
		FROM ITH_TBL a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
		INNER JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		where MITM_MODEL ='1' AND ITH_QTY >0 AND ITH_WH='AFWH3'  AND SER_DOC like '%' + @jobno + '%' and (ITH_LUPDT between @dt and @dt2)
		and ITH_FORM='INC-WH-FG'
		GROUP BY SER_DOC,SER_ITMID
		ORDER BY  SER_DOC ASC	
	end
else if (@dt is not null and @jobno is null)
	begin
		SELECT SER_DOC,SER_ITMID
		,sum(SER_QTY) QTY_WH
		FROM ITH_TBL a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
		INNER JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		where MITM_MODEL ='1' AND ITH_QTY >0 AND ITH_WH='AFWH3'  and (ITH_LUPDT between @dt and @dt2)
		and ITH_FORM='INC-WH-FG'
		GROUP BY SER_DOC,SER_ITMID
		ORDER BY  SER_DOC ASC		
	end
else if (@dt is null and @jobno is null)
	begin
		SELECT SER_DOC,SER_ITMID
		,sum(SER_QTY) QTY_WH
		FROM ITH_TBL a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
		INNER JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		where MITM_MODEL ='1' AND ITH_QTY >0 AND ITH_WH='AFWH3' 
		and ITH_FORM='INC-WH-FG'
		GROUP BY SER_DOC,SER_ITMID
		ORDER BY  SER_DOC ASC
	end


GO
/****** Object:  StoredProcedure [dbo].[sp_rincoming_fg_prd_qc]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_rincoming_fg_prd_qc]
@jobno varchar(50),
@sts varchar(50),
@pbg varchar(20)
as
if (@sts='1' and @jobno is not null ) 
	begin
		SELECT PDPP_WONO SER_DOC, PDPP_MDLCD SER_ITMID, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,
		SUM(case when  ITH_WH='ARPRD1' AND ITH_FORM= 'INC-PRD-FG' THEN ITH_QTY
		END) QTY_PRD
		,sum(case when  ITH_WH='ARQA1' AND ITH_FORM='INC-QA-FG' THEN ITH_QTY
		END) QTY_QA
		,sum(case when  ITH_WH='AFWH3' AND ITH_FORM='INC-WH-FG' THEN ITH_QTY
		END) QTY_WH,PDPP_BSGRP
		,sum(case when  ITH_WH='ARQA1' AND isnull(SER_RMRK,'') !='' AND ITH_FORM='INC-QA-FG' THEN ITH_QTY
		END) NG,
		PDPP_BOMRV
		FROM ITH_TBL a 
		RIGHT JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		RIGHT JOIN (select PDPP_WONO,PDPP_WORQT,PDPP_MDLCD,PDPP_GRNQT,PDPP_COMFG,PDPP_BSGRP,PDPP_BOMRV from XWO where PDPP_BSGRP=@pbg  ) v1 ON c.SER_DOC=v1.PDPP_WONO
		LEFT join MITM_TBL b on PDPP_MDLCD=b.MITM_ITMCD
		where ( ISNULL(MITM_MODEL,'') ='1' AND PDPP_WONO like '%'+@jobno+'%'	
		and ISNULL(ITH_FORM,'') IN ('INC-PRD-FG', 'INC-QA-FG','INC-WH-FG')
		) OR
		( ISNULL(MITM_MODEL,'') ='1' AND PDPP_WONO like '%'+@jobno+'%'	
		
		) 
		
		GROUP BY PDPP_WONO,PDPP_MDLCD, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,PDPP_COMFG,PDPP_BSGRP,PDPP_BOMRV
		having (ISNULL(SUM(case when  ITH_WH='ARPRD1' AND ITH_FORM= 'INC-PRD-FG' THEN ITH_QTY --SER_QTY
		END),0)>=PDPP_WORQT and (SUM(case when  ITH_WH='ARPRD1' THEN ITH_QTY	END) > sum(case when  ITH_WH='ARQA1' THEN ITH_QTY		END)) )
		OR (PDPP_WORQT=PDPP_GRNQT and SUM(case when  ITH_WH='ARPRD1' THEN ITH_QTY	END) > sum(case when  ITH_WH='ARQA1' THEN ITH_QTY		END)) 
		OR (PDPP_COMFG='1' and SUM(case when  ITH_WH='ARPRD1' THEN ITH_QTY	END) > sum(case when  ITH_WH='ARQA1' THEN ITH_QTY		END))
		ORDER BY SUBSTRING(PDPP_WONO,1,7) DESC, SER_ITMID
	end
else if (@sts ='0' and @jobno is not null)
	begin
		SELECT PDPP_WONO SER_DOC,PDPP_MDLCD SER_ITMID, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,
		SUM(case when  ITH_WH='ARPRD1' THEN ITH_QTY
		END) QTY_PRD
		,sum(case when  ITH_WH='ARQA1' THEN ITH_QTY
		END) QTY_QA
		,sum(case when  ITH_WH='AFWH3' THEN ITH_QTY
		END) QTY_WH,PDPP_BSGRP
		,sum(case when  ITH_WH='ARQA1' AND isnull(SER_RMRK,'') !='' AND ITH_FORM='INC-QA-FG' THEN ITH_QTY
		END) NG,
		PDPP_BOMRV
		FROM ITH_TBL a 
		RIGHT JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		RIGHT JOIN (select PDPP_WONO,PDPP_WORQT,PDPP_MDLCD,PDPP_GRNQT,PDPP_BSGRP,PDPP_BOMRV from XWO WHERE PDPP_COMFG='0' and PDPP_BSGRP=@pbg ) v1 ON c.SER_DOC=v1.PDPP_WONO
		LEFT join MITM_TBL b on PDPP_MDLCD=b.MITM_ITMCD
		where (MITM_MODEL ='1' AND (ITH_QTY !=0  OR ITH_QTY IS NULL)
		AND PDPP_WONO like '%'+@jobno+'%'
		and (ITH_FORM IN ('INC-PRD-FG', 'INC-QA-FG','INC-WH-FG','FG-BGN') OR ITH_FORM IS NULL))
		and PDPP_WORQT!=PDPP_GRNQT
		GROUP BY PDPP_WONO,PDPP_MDLCD, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,PDPP_BSGRP,PDPP_BOMRV
		having ISNULL(SUM(case when  ITH_WH='ARPRD1' THEN ITH_QTY --SER_QTY
		END),0)<PDPP_WORQT and SUM(case when  ITH_WH='ARPRD1' THEN ITH_QTY	END) > sum(case when  ITH_WH='ARQA1' THEN ITH_QTY		END)
		ORDER BY SUBSTRING(PDPP_WONO,1,7) DESC, SER_ITMID
	end
else if (@sts ='1' and @jobno is null)
	begin
		SELECT TOP 10 PDPP_WONO SER_DOC,PDPP_MDLCD SER_ITMID, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,
		SUM(case when  ITH_WH='ARPRD1' THEN SER_QTY
		END) QTY_PRD
		,sum(case when  ITH_WH='ARQA1' THEN SER_QTY
		END) QTY_QA
		,sum(case when  ITH_WH='AFWH3' THEN SER_QTY
		END) QTY_WH
		FROM ITH_TBL a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
		INNER JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		INNER JOIN (select PDPP_WONO,PDPP_WORQT,PDPP_MDLCD,PDPP_GRNQT from XWO ) v1 ON c.SER_DOC=v1.PDPP_WONO
		where MITM_MODEL ='1' AND ITH_QTY >0  AND SER_DOC like '%' + @jobno + '%'
		and ITH_FORM IN ('INC-PRD-FG', 'INC-QA-FG','INC-WH-FG')
		GROUP BY PDPP_WONO,PDPP_MDLCD, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT
		having SUM(case when  ITH_WH='ARPRD1' THEN SER_QTY
		END)<PDPP_WORQT
		ORDER BY SER_ITMID ASC , SER_DOC ASC
	end
else if (@sts ='0' and @jobno is null)
	begin
		SELECT TOP 10 PDPP_WONO SER_DOC,PDPP_MDLCD SER_ITMID, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,
		SUM(case when  ITH_WH='ARPRD1' THEN SER_QTY
		END) QTY_PRD
		,sum(case when  ITH_WH='ARQA1' THEN SER_QTY
		END) QTY_QA
		,sum(case when  ITH_WH='AFWH3' THEN SER_QTY
		END) QTY_WH
		FROM ITH_TBL a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
		INNER JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		INNER JOIN (select PDPP_WONO,PDPP_WORQT,PDPP_MDLCD,PDPP_GRNQT from XWO) v1 ON c.SER_DOC=v1.PDPP_WONO
		where MITM_MODEL ='1' AND ITH_QTY >0  AND SER_DOC like '%' + @jobno + '%'
		and ITH_FORM IN ('INC-PRD-FG', 'INC-QA-FG','INC-WH-FG')
		GROUP BY PDPP_WONO,PDPP_MDLCD, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT
		having SUM(case when  ITH_WH='ARPRD1' THEN SER_QTY
		END)<PDPP_WORQT
		ORDER BY SER_ITMID ASC , SER_DOC ASC
	end
else if (@sts is not null and @jobno is not null)
	begin
		SELECT PDPP_WONO SER_DOC,PDPP_MDLCD SER_ITMID, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,
		SUM(case when  ITH_WH='ARPRD1' THEN SER_QTY
		END) QTY_PRD
		,sum(case when  ITH_WH='ARQA1' THEN SER_QTY
		END) QTY_QA
		,sum(case when  ITH_WH='AFWH3' THEN SER_QTY
		END) QTY_WH
		FROM ITH_TBL a 
		RIGHT JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		RIGHT JOIN (select PDPP_WONO,PDPP_WORQT,PDPP_MDLCD,PDPP_GRNQT from XWO ) v1 ON c.SER_DOC=v1.PDPP_WONO
		LEFT join MITM_TBL b on PDPP_MDLCD=b.MITM_ITMCD
		where MITM_MODEL ='1' AND (ITH_QTY >0  OR ITH_QTY IS NULL)
		AND PDPP_WONO like '%' + @jobno + '%'
		and (ITH_FORM IN ('INC-PRD-FG', 'INC-QA-FG','INC-WH-FG') OR ITH_FORM IS NULL)
		GROUP BY PDPP_WONO,PDPP_MDLCD, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT
		having ISNULL(SUM(case when  ITH_WH='ARPRD1' THEN SER_QTY
		END),0)<PDPP_WORQT
		ORDER BY SER_ITMID ASC , SER_DOC ASC
	end
else if (@sts is not null and @jobno is null)
	begin
		SELECT TOP 10 PDPP_WONO SER_DOC,PDPP_MDLCD SER_ITMID, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,
		SUM(case when  ITH_WH='ARPRD1' THEN SER_QTY
		END) QTY_PRD
		,sum(case when  ITH_WH='ARQA1' THEN SER_QTY
		END) QTY_QA
		,sum(case when  ITH_WH='AFWH3' THEN SER_QTY
		END) QTY_WH
		FROM ITH_TBL a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
		INNER JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		INNER JOIN (select PDPP_WONO,PDPP_WORQT,PDPP_MDLCD,PDPP_GRNQT from XWO) v1 ON c.SER_DOC=v1.PDPP_WONO
		where MITM_MODEL ='1' AND ITH_QTY >0  
		and ITH_FORM IN ('INC-PRD-FG', 'INC-QA-FG','INC-WH-FG')
		GROUP BY PDPP_WONO,PDPP_MDLCD, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT
		ORDER BY SER_ITMID ASC , SER_DOC ASC
	end
else if (@sts is null and @jobno is null)
	begin
		SELECT PDPP_WONO SER_DOC,PDPP_MDLCD SER_ITMID, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,
		SUM(case when  ITH_WH='ARPRD1' THEN SER_QTY
		END) QTY_PRD
		,sum(case when  ITH_WH='ARQA1' THEN SER_QTY
		END) QTY_QA
		,sum(case when  ITH_WH='AFWH3' THEN SER_QTY
		END) QTY_WH
		FROM ITH_TBL a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
		INNER JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		INNER JOIN (select PDPP_WONO,PDPP_WORQT,PDPP_MDLCD,PDPP_GRNQT from XWO) v1 ON c.SER_DOC=v1.PDPP_WONO
		where MITM_MODEL ='1' AND ITH_QTY >0 
		and ITH_FORM IN ('INC-PRD-FG', 'INC-QA-FG','INC-WH-FG')
		GROUP BY PDPP_WONO,PDPP_MDLCD, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT
		ORDER BY SER_ITMID ASC , SER_DOC ASC
	end
GO
/****** Object:  StoredProcedure [dbo].[sp_rincoming_fg_with_rev]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_rincoming_fg_with_rev]
@jobno varchar(50),
@sts varchar(50),
@pbg varchar(20),
@rev varchar(20)
as
if (@sts='1' and @jobno is not null ) 
	begin
		SELECT RTRIM(PDPP_WONO) SER_DOC, PDPP_MDLCD SER_ITMID, RTRIM(MITM_ITMD1) MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,
		SUM(case when  ITH_WH='ARPRD1' AND ITH_FORM= 'INC-PRD-FG' THEN ITH_QTY
		END) QTY_PRD
		,sum(case when  ITH_WH='ARQA1' AND ITH_FORM='INC-QA-FG' THEN SER_QTYLOT
		END) QTY_QA
		,sum(case when  ITH_WH='AFWH3' AND ITH_FORM='INC-WH-FG' THEN SER_QTYLOT
		END) QTY_WH,PDPP_BSGRP
		,sum(case when  ITH_WH='ARQA1' AND (isnull(SER_RMRK,'') LIKE '%SCRAP%' ) AND ITH_FORM='INC-QA-FG' THEN ITH_QTY
		END) NG
		,ISNULL(MAX(SCRQTY),0) SCRQTY,
		PDPP_BOMRV
		FROM ITH_TBL a 
		RIGHT JOIN SER_TBL c on a.ITH_SER=c.SER_ID --and c.SER_ID=ISNULL(c.SER_REFNO,c.SER_ID)
		RIGHT JOIN (select PDPP_WONO,PDPP_WORQT,PDPP_MDLCD,PDPP_GRNQT,PDPP_COMFG,PDPP_BSGRP,PDPP_BOMRV from XWO where PDPP_BSGRP=@pbg  ) v1 ON c.SER_DOC=v1.PDPP_WONO
		LEFT join MITM_TBL b on PDPP_MDLCD=b.MITM_ITMCD
		LEFT JOIN (SELECT DOC_NO,SUM(QTY) SCRQTY FROM VRPSCRAP_HIST GROUP BY DOC_NO) AS VSCR ON PDPP_WONO=DOC_NO		
		where ( ISNULL(MITM_MODEL,'') ='1' AND PDPP_WONO like '%'+@jobno+'%'	
		and ISNULL(ITH_FORM,'') IN ('INC-PRD-FG', 'INC-QA-FG','INC-WH-FG') AND PDPP_BOMRV=@rev
		) OR
		( ISNULL(MITM_MODEL,'') ='1' AND PDPP_WONO like '%'+@jobno+'%'	AND PDPP_BOMRV=@rev
		
		) 		
		GROUP BY PDPP_WONO,PDPP_MDLCD, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,PDPP_COMFG,PDPP_BSGRP,PDPP_BOMRV
		having ISNULL(SUM(case when  ITH_WH='ARPRD1' AND ITH_FORM= 'INC-PRD-FG' THEN ITH_QTY --SER_QTY
		END),0)>=PDPP_WORQT OR PDPP_WORQT=PDPP_GRNQT OR PDPP_COMFG='1'
		ORDER BY SUBSTRING(PDPP_WONO,1,7) DESC, SER_ITMID
	end
else if (@sts ='0' and @jobno is not null)
	begin
		SELECT RTRIM(PDPP_WONO) SER_DOC,PDPP_MDLCD SER_ITMID, RTRIM(MITM_ITMD1) MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,
		SUM(case when  ITH_WH='ARPRD1' AND ITH_FORM= 'INC-PRD-FG' THEN ITH_QTY
		END) QTY_PRD
		,sum(case when  ITH_WH='ARQA1' and  c.SER_ID=ISNULL(c.SER_REFNO,c.SER_ID) THEN ITH_QTY
		END) QTY_QA
		,sum(case when  ITH_WH='AFWH3' AND ITH_FORM='INC-WH-FG' and c.SER_ID=ISNULL(c.SER_REFNO,c.SER_ID) THEN ITH_QTY
		END) QTY_WH,PDPP_BSGRP
		,sum(case when  ITH_WH='ARQA1' AND isnull(SER_RMRK,'') LIKE '%SCRAP%' AND ITH_FORM='INC-QA-FG' THEN ITH_QTY
		END) NG
		,ISNULL(MAX(SCRQTY),0) SCRQTY,
		PDPP_BOMRV
		FROM ITH_TBL a 
		RIGHT JOIN SER_TBL c on a.ITH_SER=c.SER_ID 
		RIGHT JOIN (select PDPP_WONO,PDPP_WORQT,PDPP_MDLCD,PDPP_GRNQT,PDPP_BSGRP,PDPP_BOMRV from XWO WHERE PDPP_COMFG='0' and PDPP_BSGRP=@pbg ) v1 ON c.SER_DOC=v1.PDPP_WONO
		LEFT join MITM_TBL b on PDPP_MDLCD=b.MITM_ITMCD
		LEFT JOIN (SELECT DOC_NO,SUM(QTY) SCRQTY FROM VRPSCRAP_HIST GROUP BY DOC_NO) AS VSCR ON PDPP_WONO=DOC_NO
		where (MITM_MODEL ='1' AND (ITH_QTY !=0  OR ITH_QTY IS NULL)
		AND PDPP_WONO like '%'+@jobno+'%'
		and (ITH_FORM IN ('INC-PRD-FG', 'INC-QA-FG','INC-WH-FG','FG-BGN') OR ITH_FORM IS NULL))
		and PDPP_WORQT!=PDPP_GRNQT AND PDPP_BOMRV=@rev
		GROUP BY PDPP_WONO,PDPP_MDLCD, MITM_ITMD1,PDPP_WORQT,PDPP_GRNQT,PDPP_BSGRP,PDPP_BOMRV
		having ISNULL(SUM(case when  ITH_WH='ARPRD1' THEN ITH_QTY --SER_QTY
		END),0)<PDPP_WORQT
		ORDER BY SUBSTRING(PDPP_WONO,1,7) DESC, SER_ITMID
	end
GO
/****** Object:  StoredProcedure [dbo].[sp_rincoming_fgrtn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_rincoming_fgrtn] 
@bg varchar(50),
@docno varchar(50),
@itemcd varchar(50),
@sts varchar(1)
as

IF (@sts = '1')
BEGIN
  SELECT
    RTRIM(MBSG_DESC) MBSG_DESC,
    RETFG_DOCNO,
    RCV_RPNO,
    RCV_BCNO,
    RETFG_NTCNO,
    RETFG_ITMCD,
    CUSTITMCD,
    RTRIM(MITM_ITMD1) MITM_ITMD1,
    SUM(RETFG_QTY) RETFG_QTY,
    SUM(SER_QTY) SER_QTY,
    ISNULL(SUM(RCQTY), 0) RCQTY,
    ISNULL(SUM(QCQTY), 0) QCQTY,
    ISNULL(SUM(WHQTY), 0) WHQTY
  FROM RETFG_TBL
  LEFT JOIN XVU_RTN
    ON RETFG_DOCNO = STKTRND1_DOCNO
  LEFT JOIN (SELECT DISTINCT
    RCV_DONO,
    RCV_RPNO,
    RCV_BCNO
  FROM RCV_TBL) VRCV
    ON RETFG_DOCNO = RCV_DONO
  LEFT JOIN MITM_TBL
    ON RETFG_ITMCD = MITM_ITMCD
  LEFT JOIN SER_TBL
    ON RETFG_DOCNO = SER_DOC
    AND RETFG_LINE = SER_PRDLINE
  LEFT JOIN (SELECT ITH_ITMCD CUSTITMCD,INTLBL,
  ISNULL(SUM(CASE
      WHEN ITH_FORM in ('INC-RCRTN-FG','INC-SCRRTN-FG') THEN INTLBLQTY
    END), 0) RCQTY
  , ISNULL(SUM(CASE
      WHEN ITH_FORM = 'INC-QCRTN-FG' THEN INTLBLQTY
    END), 0) QCQTY
	,ISNULL(SUM(CASE
      WHEN ITH_FORM = 'INC-WHRTN-FG' THEN INTLBLQTY
    END), 0) WHQTY
  FROM
  (SELECT  *
  FROM ITH_TBL
  WHERE ITH_FORM IN ('INC-RCRTN-FG', 'INC-QCRTN-FG', 'INC-WHRTN-FG','INC-SCRRTN-FG')
  ) VITHRTN 
  LEFT JOIN (
  SELECT ITH_REMARK EXTLBL, ITH_SER INTLBL,ABS(ITH_QTY) INTLBLQTY FROM ITH_TBL WHERE ITH_WH='AFQART' AND ITH_QTY<0 AND ITH_REMARK IS NOT NULL
  ) VINTLBL ON VITHRTN.ITH_SER=VINTLBL.EXTLBL
  GROUP BY ITH_ITMCD,INTLBL) VSER
    ON SER_ID = INTLBL
LEFT JOIN (
	  SELECT ITH_SER INTLBLR,SUM(ABS(ITH_QTY)) INTLBLRQTY FROM ITH_TBL WHERE ITH_WH='AFQART' AND ITH_QTY<0 AND ITH_REMARK IS NOT NULL
	  GROUP BY  ITH_SER
	  ) VINTLBLR ON SER_ID=INTLBLR
  WHERE MBSG_BSGRP = @bg
  AND RETFG_DOCNO LIKE '%' + @docno + '%'
  AND RETFG_ITMCD LIKE '%' + @itemcd + '%'
  --AND RETFG_QTY = ISNULL(QCQTY, 0)
  GROUP BY RTRIM(MBSG_DESC),
           RETFG_DOCNO,
           RCV_RPNO,
           RCV_BCNO,
           RETFG_NTCNO,
           RETFG_ITMCD,
           CUSTITMCD,
           RTRIM(MITM_ITMD1)
  HAVING SUM(RETFG_QTY) = SUM(INTLBLRQTY) --ISNULL(SUM(RCQTY), 0)
  ORDER BY RETFG_DOCNO, RETFG_ITMCD
END
ELSE
BEGIN
  SELECT
    RTRIM(MBSG_DESC) MBSG_DESC,
    RETFG_DOCNO,
    RCV_RPNO,
    RCV_BCNO,
    RETFG_NTCNO,
    RETFG_ITMCD,
    CUSTITMCD,
    RTRIM(MITM_ITMD1) MITM_ITMD1,
    SUM(RETFG_QTY) RETFG_QTY,
    SUM(SER_QTY) SER_QTY,
    ISNULL(SUM(RCQTY), 0) RCQTY,
    ISNULL(SUM(QCQTY), 0) QCQTY,
    ISNULL(SUM(WHQTY), 0) WHQTY
  FROM RETFG_TBL
  LEFT JOIN XVU_RTN
    ON RETFG_DOCNO = STKTRND1_DOCNO

  LEFT JOIN (SELECT DISTINCT
    RCV_DONO,
    RCV_RPNO,
    RCV_BCNO
  FROM RCV_TBL) VRCV
    ON RETFG_DOCNO = RCV_DONO
  LEFT JOIN MITM_TBL
    ON RETFG_ITMCD = MITM_ITMCD
  LEFT JOIN SER_TBL
    ON RETFG_DOCNO = SER_DOC
    AND RETFG_LINE = SER_PRDLINE
  LEFT JOIN (SELECT ITH_ITMCD CUSTITMCD,INTLBL,
  ISNULL(SUM(CASE
      WHEN ITH_FORM in ('INC-RCRTN-FG','INC-SCRRTN-FG') THEN INTLBLQTY
    END), 0) RCQTY
  , ISNULL(SUM(CASE
      WHEN ITH_FORM = 'INC-QCRTN-FG' THEN INTLBLQTY
    END), 0) QCQTY
	,ISNULL(SUM(CASE
      WHEN ITH_FORM = 'INC-WHRTN-FG' THEN INTLBLQTY
    END), 0) WHQTY
  FROM
  (SELECT  *
  FROM ITH_TBL
  WHERE ITH_FORM IN ('INC-RCRTN-FG', 'INC-QCRTN-FG', 'INC-WHRTN-FG','INC-SCRRTN-FG')
  ) VITHRTN 
  LEFT JOIN (
  SELECT ITH_REMARK EXTLBL, ITH_SER INTLBL,ABS(ITH_QTY) INTLBLQTY FROM ITH_TBL WHERE ITH_WH='AFQART' AND ITH_QTY<0 AND ITH_REMARK IS NOT NULL
  ) VINTLBL ON VITHRTN.ITH_SER=VINTLBL.EXTLBL
  GROUP BY ITH_ITMCD,INTLBL) VSER
    ON SER_ID = INTLBL
LEFT JOIN (
	  SELECT ITH_SER INTLBLR,SUM(ABS(ITH_QTY)) INTLBLRQTY FROM ITH_TBL WHERE ITH_WH='AFQART' AND ITH_QTY<0 AND ITH_REMARK IS NOT NULL
	  GROUP BY  ITH_SER
	  ) VINTLBLR ON SER_ID=INTLBLR
  WHERE MBSG_BSGRP = @bg
  AND RETFG_DOCNO LIKE '%' + @docno + '%'
  AND RETFG_ITMCD LIKE '%' + @itemcd + '%'
  GROUP BY RTRIM(MBSG_DESC),
           RETFG_DOCNO,
           RCV_RPNO,
           RCV_BCNO,
           RETFG_NTCNO,
           RETFG_ITMCD,
           CUSTITMCD,
           RTRIM(MITM_ITMD1)
  HAVING SUM(RETFG_QTY) != SUM(INTLBLRQTY) --ISNULL(SUM(RCQTY), 0)
  ORDER BY RETFG_DOCNO, RETFG_ITMCD
END
GO
/****** Object:  StoredProcedure [dbo].[sp_searchdo_all]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE procedure [dbo].[sp_searchdo_all]
@do varchar(11)
as
SELECT * from
(SELECT RCV_DONO , count(*) TTLITEM, SUM(RCV_QTY) TTLREQ, SUM(ISNULL(SCNQTY,0)) TTLACT FROM
(SELECT a.RCV_DONO,a.RCV_ITMCD,a.RCV_QTY,SUM(b.RCVSCN_QTY) SCNQTY FROM vrcv_tblg a left join RCVSCN_TBL b on a.RCV_DONO=b.RCVSCN_DONO and a.RCV_ITMCD=b.RCVSCN_ITMCD
GROUP BY a.RCV_DONO,a.RCV_ITMCD,a.RCV_QTY) v1
GROUP BY RCV_DONO ) v2 inner join 
(select RCV_DONO,RCV_SUPCD,MSUP_SUPNM,MAX(RCV_RCVDATE) TGL FROM RCV_TBL d left join MSUP_TBL e on
d.RCV_SUPCD=e.MSUP_SUPCD
GROUP BY RCV_DONO, RCV_SUPCD,MSUP_SUPNM) v3 on v2.RCV_DONO=v3.RCV_DONO
WHERE v2.RCV_DONO like concat('%',@do,'%')
ORDER BY TGL ASC, v2.RCV_DONO ASC

GO
/****** Object:  StoredProcedure [dbo].[sp_searchdo_open]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE procedure [dbo].[sp_searchdo_open]
@do varchar(11)
as
SELECT * from
(SELECT RCV_DONO , count(*) TTLITEM, SUM(RCV_QTY) TTLREQ, SUM(ISNULL(SCNQTY,0)) TTLACT FROM
(SELECT a.RCV_DONO,a.RCV_ITMCD,a.RCV_QTY,SUM(b.RCVSCN_QTY) SCNQTY FROM vrcv_tblg a left join RCVSCN_TBL b on a.RCV_DONO=b.RCVSCN_DONO and a.RCV_ITMCD=b.RCVSCN_ITMCD
GROUP BY a.RCV_DONO,a.RCV_ITMCD,a.RCV_QTY) v1
GROUP BY RCV_DONO ) v2 inner join 
(select RCV_DONO,RCV_SUPCD,MSUP_SUPNM,MAX(RCV_RCVDATE) TGL FROM RCV_TBL d left join MSUP_TBL e on
d.RCV_SUPCD=e.MSUP_SUPCD
GROUP BY RCV_DONO, RCV_SUPCD,MSUP_SUPNM) v3 on v2.RCV_DONO=v3.RCV_DONO
WHERE v2.RCV_DONO like concat('%',@do,'%') and TTLACT<TTLREQ
ORDER BY TGL ASC, v2.RCV_DONO ASC


GO
/****** Object:  StoredProcedure [dbo].[sp_serah_terima_mth_exim]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
create procedure [dbo].[sp_serah_terima_mth_exim]
@sidoc varchar(50)
as
SELECT V1.*,TTLQTY,SCAN_DATE FROM
(SELECT SER_ITMID,SISCN_SERQTY,COUNT(*) TTLBOX, MIN(CONVERT(DATE,SISCN_LUPDT)) SCAN_DATE FROM SISCN_TBL 
LEFT JOIN SER_TBL ON SISCN_SER=SER_ID
WHERE SISCN_DOC=@sidoc
GROUP BY SER_ITMID,SISCN_SERQTY) V1
LEFT JOIN
(SELECT SER_ITMID,SUM(SISCN_SERQTY) TTLQTY FROM SISCN_TBL 
LEFT JOIN SER_TBL ON SISCN_SER=SER_ID
WHERE SISCN_DOC=@sidoc
GROUP BY SER_ITMID) V2 ON V1.SER_ITMID=V2.SER_ITMID
ORDER BY V1.SER_ITMID
GO
/****** Object:  StoredProcedure [dbo].[sp_serfg_suggestion]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_serfg_suggestion]
@itmcd varchar(50),
@wh varchar(50)
as 
--SELECT top 1 * FROM ITH_TBL a INNER JOIN
--(SELECT ITH_SER, MAX(ITH_LUPDT) LUPDT FROM ITH_TBL WHERE ITH_ITMCD =@itmcd
--GROUP BY ITH_SER ) v1 on a.ITH_LUPDT=v1.LUPDT AND a.ITH_SER=v1.ITH_SER
--WHERE ITH_FORM='INC-WH-FG' AND a.ITH_SER NOT IN 
--(
--SELECT SISCN_SER FROM SISCN_TBL
--)
--order by ITH_LUPDT ASC
SELECT top 1 * FROM ITH_TBL a INNER JOIN
(SELECT ITH_SER, MAX(ITH_LUPDT) LUPDT FROM ITH_TBL WHERE ITH_ITMCD =@itmcd and ITH_WH=@wh
GROUP BY ITH_SER HAVING SUM(ITH_QTY)>0 ) v1 on a.ITH_LUPDT=v1.LUPDT AND a.ITH_SER=v1.ITH_SER
WHERE ITH_QTY>0 AND a.ITH_SER NOT IN 
(
SELECT SISCN_SER FROM SISCN_TBL
)
order by ITH_LUPDT ASC
GO
/****** Object:  StoredProcedure [dbo].[sp_serfg_suggestion_all]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[sp_serfg_suggestion_all]
@itmcd varchar(50)
as 
SELECT * FROM ITH_TBL a INNER JOIN
(SELECT ITH_SER, MAX(ITH_LUPDT) LUPDT FROM ITH_TBL WHERE ITH_ITMCD =@itmcd
GROUP BY ITH_SER ) v1 on a.ITH_LUPDT=v1.LUPDT AND a.ITH_SER=v1.ITH_SER
WHERE ITH_FORM='INC-WH-FG' AND a.ITH_SER NOT IN 
(
SELECT SISCN_SER FROM SISCN_TBL
)
order by ITH_LUPDT ASC
GO
/****** Object:  StoredProcedure [dbo].[sp_serfg_suggestion_alt]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_serfg_suggestion_alt]
@itmcd varchar(50),
@serid varchar(10),
@wh varchar(20)
as 
SELECT * FROM ITH_TBL a INNER JOIN
(SELECT ITH_SER, MAX(ITH_LUPDT) LUPDT FROM ITH_TBL WHERE ITH_ITMCD =@itmcd and ITH_SER != @serid and ITH_WH=@wh
GROUP BY ITH_SER HAVING SUM(ITH_QTY)>0 ) v1 on a.ITH_LUPDT=v1.LUPDT AND a.ITH_SER=v1.ITH_SER
WHERE a.ITH_QTY>0 AND a.ITH_SER NOT IN 
(
SELECT SISCN_SER FROM SISCN_TBL
)
order by ITH_LUPDT ASC
GO
/****** Object:  StoredProcedure [dbo].[sp_splvsret]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--select * from SPLSCN_TBL WHERE SPLSCN_DOC='SP-MAT-2019-12-0050'

CREATE procedure [dbo].[sp_splvsret]
@doc varchar(50),
@cat varchar(50),
@lne varchar(50),
@fdr varchar(5),
@itm varchar(50)
as
select v1.*, COALESCE(RETQTY,0) RETQTY  from 
(
SELECT SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR,SPLSCN_ORDERNO, SPLSCN_ITMCD,sum(SPLSCN_QTY) SPLSCN_QTY,SPLSCN_LOTNO FROM SPLSCN_TBL
WHERE SPLSCN_DOC=@doc AND SPLSCN_CAT=@cat AND SPLSCN_LINE = @lne AND SPLSCN_FEDR = @fdr AND SPLSCN_ITMCD=@itm
GROUP BY SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR, SPLSCN_ITMCD,SPLSCN_LOTNO,SPLSCN_ORDERNO)
v1 LEFT join (
SELECT RETSCN_SPLDOC, RETSCN_CAT, RETSCN_LINE, RETSCN_FEDR,RETSCN_ORDERNO, RETSCN_ITMCD,RETSCN_LOT,SUM(RETSCN_QTYBEF) RETQTY FROM RETSCN_TBL
WHERE RETSCN_SPLDOC=@doc AND RETSCN_CAT=@cat AND RETSCN_LINE =@lne AND RETSCN_FEDR = @fdr AND RETSCN_ITMCD=@itm
GROUP BY RETSCN_SPLDOC, RETSCN_CAT, RETSCN_LINE, RETSCN_FEDR, RETSCN_ITMCD,RETSCN_LOT,RETSCN_ORDERNO) v2 
on v1.SPLSCN_DOC=v2.RETSCN_SPLDOC and  v1.SPLSCN_CAT=v2.RETSCN_CAT and v1.SPLSCN_LINE=v2.RETSCN_LINE and v1.SPLSCN_FEDR=v2.RETSCN_FEDR
and v1.SPLSCN_ITMCD=v2.RETSCN_ITMCD AND SPLSCN_LOTNO=RETSCN_LOT AND SPLSCN_ORDERNO = RETSCN_ORDERNO
ORDER BY SPLSCN_ORDERNO ASC,SPLSCN_ITMCD ASC
--select v1.*, COALESCE(TTLITEMRET,0) TTLITEMRET from 
--(
--SELECT SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR,SPLSCN_ORDERNO, SPLSCN_ITMCD,SPLSCN_QTY,SPLSCN_LOTNO,SUM(SPLSCN_QTY) SUPQTY,COUNT(*) TTLITEM FROM SPLSCN_TBL
--WHERE SPLSCN_DOC=@doc AND SPLSCN_CAT=@cat AND SPLSCN_LINE = @lne AND SPLSCN_FEDR = @fdr AND SPLSCN_ITMCD=@itm
--GROUP BY SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR, SPLSCN_ITMCD,SPLSCN_LOTNO,SPLSCN_QTY,SPLSCN_ORDERNO)
--v1 LEFT join (
--SELECT RETSCN_SPLDOC, RETSCN_CAT, RETSCN_LINE, RETSCN_FEDR,RETSCN_ORDERNO, RETSCN_ITMCD,RETSCN_LOT,RETSCN_QTYBEF,SUM(RETSCN_QTYAFT) RETQTY,COUNT(*) TTLITEMRET FROM RETSCN_TBL
--WHERE RETSCN_SPLDOC=@doc AND RETSCN_CAT=@cat AND RETSCN_LINE =@lne AND RETSCN_FEDR = @fdr AND RETSCN_ITMCD=@itm
--GROUP BY RETSCN_SPLDOC, RETSCN_CAT, RETSCN_LINE, RETSCN_FEDR, RETSCN_ITMCD,RETSCN_LOT,RETSCN_QTYBEF,RETSCN_ORDERNO) v2 
--on v1.SPLSCN_DOC=v2.RETSCN_SPLDOC and  v1.SPLSCN_CAT=v2.RETSCN_CAT and v1.SPLSCN_LINE=v2.RETSCN_LINE and v1.SPLSCN_FEDR=v2.RETSCN_FEDR
--and v1.SPLSCN_ITMCD=v2.RETSCN_ITMCD AND SPLSCN_LOTNO=RETSCN_LOT AND SPLSCN_QTY=RETSCN_QTYBEF AND
--SPLSCN_ORDERNO = RETSCN_ORDERNO
GO
/****** Object:  StoredProcedure [dbo].[sp_splvsret_nofr]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
--select * from SPLSCN_TBL WHERE SPLSCN_DOC='SP-MAT-2019-12-0050'

CREATE procedure [dbo].[sp_splvsret_nofr]
@doc varchar(50),
@cat varchar(50),
@lne varchar(50),
@itm varchar(50)
as
select v1.*, ISNULL(RETQTY,0) RETQTY, ISNULL(CTRQTY,0) CTRQTY, CONVERT(bigint, ISNULL(SPLSCN_QTY,0)-ISNULL(CTRQTY,0)) RLOGICQTY,RTRIM(MITM_SPTNO) MITM_SPTNO  from 
(
SELECT SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, RTRIM(SPLSCN_FEDR) SPLSCN_FEDR,SPLSCN_ORDERNO, RTRIM(SPLSCN_ITMCD) SPLSCN_ITMCD,sum(SPLSCN_QTY) SPLSCN_QTY,RTRIM(SPLSCN_LOTNO) SPLSCN_LOTNO FROM SPLSCN_TBL
WHERE SPLSCN_DOC=@doc AND SPLSCN_CAT=@cat AND SPLSCN_LINE = @lne AND  SPLSCN_ITMCD=@itm
GROUP BY SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR, SPLSCN_ITMCD,SPLSCN_LOTNO,SPLSCN_ORDERNO)
v1 LEFT join (
SELECT RETSCN_SPLDOC, RETSCN_CAT, RETSCN_LINE, RETSCN_FEDR,RETSCN_ORDERNO, RETSCN_ITMCD,RETSCN_LOT,SUM(RETSCN_QTYBEF) RETQTY, SUM(RETSCN_QTYAFT) CTRQTY FROM RETSCN_TBL
WHERE RETSCN_SPLDOC=@doc AND RETSCN_CAT=@cat AND RETSCN_LINE =@lne AND  RETSCN_ITMCD=@itm
GROUP BY RETSCN_SPLDOC, RETSCN_CAT, RETSCN_LINE, RETSCN_FEDR, RETSCN_ITMCD,RETSCN_LOT,RETSCN_ORDERNO) v2 
on v1.SPLSCN_DOC=v2.RETSCN_SPLDOC and  v1.SPLSCN_CAT=v2.RETSCN_CAT and v1.SPLSCN_LINE=v2.RETSCN_LINE and v1.SPLSCN_FEDR=v2.RETSCN_FEDR
and v1.SPLSCN_ITMCD=v2.RETSCN_ITMCD AND SPLSCN_LOTNO=RETSCN_LOT AND SPLSCN_ORDERNO = RETSCN_ORDERNO
LEFT JOIN MITM_TBL ON SPLSCN_ITMCD=MITM_ITMCD
ORDER BY SPLSCN_QTY ASC, SPLSCN_ORDERNO ASC,SPLSCN_ITMCD ASC
--select v1.*, COALESCE(TTLITEMRET,0) TTLITEMRET from 
--(
--SELECT SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR,SPLSCN_ORDERNO, SPLSCN_ITMCD,SPLSCN_QTY,SPLSCN_LOTNO,SUM(SPLSCN_QTY) SUPQTY,COUNT(*) TTLITEM FROM SPLSCN_TBL
--WHERE SPLSCN_DOC=@doc AND SPLSCN_CAT=@cat AND SPLSCN_LINE = @lne AND SPLSCN_FEDR = @fdr AND SPLSCN_ITMCD=@itm
--GROUP BY SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR, SPLSCN_ITMCD,SPLSCN_LOTNO,SPLSCN_QTY,SPLSCN_ORDERNO)
--v1 LEFT join (
--SELECT RETSCN_SPLDOC, RETSCN_CAT, RETSCN_LINE, RETSCN_FEDR,RETSCN_ORDERNO, RETSCN_ITMCD,RETSCN_LOT,RETSCN_QTYBEF,SUM(RETSCN_QTYAFT) RETQTY,COUNT(*) TTLITEMRET FROM RETSCN_TBL
--WHERE RETSCN_SPLDOC=@doc AND RETSCN_CAT=@cat AND RETSCN_LINE =@lne AND RETSCN_FEDR = @fdr AND RETSCN_ITMCD=@itm
--GROUP BY RETSCN_SPLDOC, RETSCN_CAT, RETSCN_LINE, RETSCN_FEDR, RETSCN_ITMCD,RETSCN_LOT,RETSCN_QTYBEF,RETSCN_ORDERNO) v2 
--on v1.SPLSCN_DOC=v2.RETSCN_SPLDOC and  v1.SPLSCN_CAT=v2.RETSCN_CAT and v1.SPLSCN_LINE=v2.RETSCN_LINE and v1.SPLSCN_FEDR=v2.RETSCN_FEDR
--and v1.SPLSCN_ITMCD=v2.RETSCN_ITMCD AND SPLSCN_LOTNO=RETSCN_LOT AND SPLSCN_QTY=RETSCN_QTYBEF AND
--SPLSCN_ORDERNO = RETSCN_ORDERNO
GO
/****** Object:  StoredProcedure [dbo].[sp_splvssupvsret]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

--select * from SPLSCN_TBL WHERE SPLSCN_DOC='SP-MAT-2019-12-0050'

CREATE procedure [dbo].[sp_splvssupvsret]
@doc varchar(50),
@cat varchar(50),
@lne varchar(50),
@fdr varchar(5)
as
select v3.*,COALESCE(TTLISSUE,0) TTLISSUE,COALESCE(TTLRET,0) TTLRET,COALESCE((TTLISSUE-TTLREQ),0) LOGIC,MITM_SPTNO from 
(SELECT SPL_DOC,SPL_CAT,SPL_LINE,SPL_FEDR,SPL_ITMCD, SUM(SPL_QTYREQ) TTLREQ
        FROM SPL_TBL WHERE SPL_DOC=@doc AND SPL_CAT=@cat AND SPL_LINE=@lne AND SPL_FEDR=@fdr 
        GROUP BY SPL_DOC,SPL_CAT,SPL_LINE,SPL_FEDR,SPL_ITMCD) v3 LEFT JOIN 
(
SELECT SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR, SPLSCN_ITMCD,sum(SPLSCN_QTY) TTLISSUE FROM SPLSCN_TBL
WHERE SPLSCN_DOC=@doc AND SPLSCN_CAT=@cat AND SPLSCN_LINE =@lne AND SPLSCN_FEDR = @fdr 
GROUP BY SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR, SPLSCN_ITMCD)
v1 ON v3.SPL_DOC=v1.SPLSCN_DOC and v3.SPL_CAT=v1.SPLSCN_CAT and v3.SPL_LINE=v1.SPLSCN_LINE and SPL_FEDR=SPLSCN_FEDR
AND SPL_ITMCD=SPLSCN_ITMCD
 LEFT join (
SELECT RETSCN_SPLDOC, RETSCN_CAT, RETSCN_LINE, RETSCN_FEDR, RETSCN_ITMCD,SUM(RETSCN_QTYAFT) TTLRET FROM RETSCN_TBL
WHERE RETSCN_SPLDOC=@doc AND RETSCN_CAT=@cat AND RETSCN_LINE =@lne AND RETSCN_FEDR =@fdr
GROUP BY RETSCN_SPLDOC, RETSCN_CAT, RETSCN_LINE, RETSCN_FEDR, RETSCN_ITMCD) v2
on v1.SPLSCN_DOC=v2.RETSCN_SPLDOC and  v1.SPLSCN_CAT=v2.RETSCN_CAT and v1.SPLSCN_LINE=v2.RETSCN_LINE and v1.SPLSCN_FEDR=v2.RETSCN_FEDR
and v1.SPLSCN_ITMCD=v2.RETSCN_ITMCD 
INNER JOIN MITM_TBL k on v3.SPL_ITMCD=k.MITM_ITMCD 
GO
/****** Object:  StoredProcedure [dbo].[sp_splvssupvsret_psnonly]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE procedure [dbo].[sp_splvssupvsret_psnonly]
@psn varchar(50)
as
SELECT SPL_ITMCD,SPL_DOC,RTRIM(MITM_SPTNO) MITM_SPTNO,SPL_QTYREQ,isnull(SCNQTY,0) SCNQTY,(isnull(SCNQTY,0)-SPL_QTYREQ) LOGIC, ISNULL(RETQTY,0) TTLRET FROM
(SELECT SPL_ITMCD,SPL_DOC,SUM(SPL_QTYREQ) SPL_QTYREQ FROM SPL_TBL WHERE SPL_DOC=@psn
GROUP BY SPL_ITMCD,SPL_DOC) v1
left join 
(
SELECT SPLSCN_ITMCD,SPLSCN_DOC,SUM(SPLSCN_QTY) SCNQTY FROM SPLSCN_TBL WHERE SPLSCN_DOC=@psn
GROUP BY  SPLSCN_ITMCD,SPLSCN_DOC) v2 on SPL_ITMCD=SPLSCN_ITMCD AND SPL_DOC=SPLSCN_DOC
LEFT JOIN (
SELECT RETSCN_ITMCD,RETSCN_SPLDOC,SUM(RETSCN_QTYAFT) RETQTY FROM RETSCN_TBL WHERE RETSCN_SPLDOC=@psn and ISNULL(RETSCN_HOLD,'0') = '0'
GROUP BY RETSCN_ITMCD,RETSCN_SPLDOC) v3 on SPL_ITMCD=RETSCN_ITMCD AND SPL_DOC=RETSCN_SPLDOC
INNER JOIN MITM_TBL ON SPL_ITMCD=MITM_ITMCD
where isnull(RETQTY,0)>0
ORDER BY SPL_DOC,SPL_ITMCD ASC
GO
/****** Object:  StoredProcedure [dbo].[sr_sp_checkJobTotalScrapQty]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sr_sp_checkJobTotalScrapQty]
	@model [varchar](100) = NULL,
	@jobSearch [varchar](100) = NULL,
	@orderby [varchar](20) = 'PIS2_WONO',
	@ordersort [varchar](4) = 'desc',
	@pagination [bit] = 0,
	@rowsPerPage [int] = 0,
	@nowPage [int] = 5,
	@wipOnly [bit] = 0,
	@scrapOnly [bit] = 0
	
AS 
BEGIN
	SET NOCOUNT ON 
	DECLARE @Command NVARCHAR(MAX), @searchWO NVARCHAR(MAX) = '', @WH varchar(10)
	SET @WH = '''AFWH9SC''';
	
	IF (@jobSearch IS NOT NULL) 
	BEGIN
		SET @searchWO = 'AND [PIS2_WONO] LIKE ''%'+ @jobSearch +'%''';
	END
	SET @Command = N'select
			RTRIM(PIS2_WONO) as PIS2_WONO,
			RTRIM(PIS2_MDLCD) as PIS2_MDLCD,
			0 AS IS_RETURN,
			(
				SELECT CAST(SUM(PDPP_WORQT) AS INT)
				FROM XWO x
				WHERE x.PDPP_WONO  = PIS2_WONO
			) AS JOB_REQ,
			( SELECT
				COALESCE(SUM(CAST(ITH_QTY AS INT)),0)
			FROM
				ITH_TBL it WITH(INDEX(NCI_ITH_IDX))
			WHERE
				it.ITH_ITMCD = PIS2_MDLCD
				AND it.ITH_WH = ' + @WH +'
			) as TOTAL_QTY_SCRAP,
			(
				SELECT
					COALESCE(SUM(QTY),
					0)
				FROM
					PSI_RPCUST.dbo.RPSCRAP_HIST
				WHERE
					DOC_NO = PIS2_WONO
					AND deleted_at IS NULL
			) as TOTAL_SCR_RPT,
			(
				(
					SELECT CAST(SUM(PDPP_WORQT) AS INT)
					FROM XWO x
					WHERE x.PDPP_WONO  = PIS2_WONO
				) - 
				( 
					SELECT
						COALESCE(SUM(CAST(ITH_QTY AS INT)),0)
					FROM
						ITH_TBL it WITH(INDEX(NCI_ITH_IDX))
					WHERE
						it.ITH_DOC = PIS2_WONO
						AND it.ITH_WH = ' + @WH +'
				) - (
					SELECT
						COALESCE(SUM(QTY),
						0)
					FROM
						PSI_RPCUST.dbo.RPSCRAP_HIST
					WHERE
						DOC_NO = PIS2_WONO
						AND deleted_at IS NULL
				)
			) AS TOTAL_QTY
		from
			(
				SELECT T.* FROM (
					SELECT PIS2_WONO, PIS2_MDLCD
					FROM 
						[SRVMEGA].[PSI_MEGAEMS].[dbo].[PIS2_TBL]
					GROUP BY
						PIS2_WONO, PIS2_MDLCD
					UNION ALL
					SELECT PDPP_WONO as PIS2_WONO, PDPP_MDLCD as PIS2_MDLCD
					FROM XWO
					GROUP BY PDPP_WONO, PDPP_MDLCD
				) T
			) AS T
		where [PIS2_WONO] LIKE ''%'+ @jobSearch +'%''
		group by
			[PIS2_WONO],
			[PIS2_MDLCD]
		having 
			(
				(
					SELECT CAST(SUM(PDPP_WORQT) AS INT)
					FROM XWO x
					WHERE x.PDPP_WONO = PIS2_WONO
				) - 
				( 
					SELECT
						COALESCE(SUM(CAST(ITH_QTY AS INT)),0)
					FROM
						ITH_TBL it WITH(INDEX(NCI_ITH_IDX))
					WHERE
						it.ITH_DOC = PIS2_WONO
						AND it.ITH_WH = ' + @WH +'
				) - (
					SELECT
						COALESCE(SUM(QTY),
						0)
					FROM
						PSI_RPCUST.dbo.RPSCRAP_HIST
					WHERE
						DOC_NO = PIS2_WONO
						AND deleted_at IS NULL
				)
			) > 0 ';
	
	if(@scrapOnly = 1)
		BEGIN
			Set @Command = CONCAT('SELECT * FROM (', @Command, ') A ', ' WHERE A.TOTAL_QTY_SCRAP > 0');
		END
	
	if(@wipOnly = 1)
		BEGIN
			Set @Command = CONCAT('SELECT * FROM (', @Command, ') A ', ' WHERE A.TOTAL_QTY > 0');
		END
		
	Set @Command = CONCAT(@Command, 'order by ' + @orderby + ' ' + @ordersort); 
	
	if(@pagination = 1)
	 	BEGIN
			Set @Command = CONCAT(@Command, ' OFFSET ', @rowsPerPage, ' ROWS FETCH NEXT ', @nowPage, ' ROWS ONLY');
		END
		
	print @Command
		
	EXECUTE SP_EXECUTESQL @Command
END
GO
/****** Object:  StoredProcedure [dbo].[WMS_SaveToCR]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	<Description,,>
-- =============================================
create PROCEDURE [dbo].[WMS_SaveToCR]
(
@wo [varchar](50),
@pro [varchar](50),
@lin [varchar](50),
@itm [varchar](50),
@lot [varchar](50),
@dt datetime,
@ID [varchar](50),
@rmk [varchar](50),
@cqty [varchar](50),
@uc [varchar](50)
)

AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
BEGIN
set @dt = GETDATE()
		INSERT INTO [wms_CR_HIS] 
		(wono,procd,[lineno],itmcd,lotno,lupdt, lupby,remark,qty,uniqecode)
		VALUES(@wo,@pro,@lin,@itm,@lot,@dt,@ID,@rmk,@cqty,@uc)
		END
END

GO
/****** Object:  StoredProcedure [dbo].[wms_sp_abnormal_kitting_tx]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_abnormal_kitting_tx]
@txdate date
as
select RTRIM(ITH_DOC) ITH_DOC,RTRIM(ITH_ITMCD) ITH_ITMCD,COUNT(*) TTLROWS from v_ith_tblc where ITH_DATEC=@txdate and ITH_WH NOT IN ('AFWH3') AND SUBSTRING(ITH_DOC,1,2) IN ('SP','PR') 
AND ITH_FORM NOT LIKE '%RET%'
GROUP BY ITH_DOC,ITH_ITMCD
having count(*)%2!=0
ORDER BY ITH_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_booked_spl_diff]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_booked_spl_diff]
@PSN VARCHAR(45)
AS
SELECT VSPL.*
	,SPLBOOK_ITMCD
FROM (
	SELECT SPL_DOC
		,SPL_ITMCD
		,SPL_CAT
		,SUM(SPL_QTYREQ) RQT
	FROM SPL_TBL
	WHERE SPL_DOC = @PSN
		AND SPL_CAT IN (
			'PCB'
			,'SP'
			)
	GROUP BY SPL_DOC
		,SPL_ITMCD
		,SPL_CAT
	) VSPL
FULL JOIN (
	SELECT *
	FROM SPLBOOK_TBL
	WHERE SPLBOOK_SPLDOC = @PSN
	) VSPLBOOK ON SPL_DOC = SPLBOOK_SPLDOC
	AND SPL_ITMCD = SPLBOOK_ITMCD
WHERE SPLBOOK_ITMCD IS NULL
	OR SPL_ITMCD IS NULL
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_check_doc]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_check_doc] 
@DOCNO varchar(25)
as
SELECT VSPLSCN.*,ISNULL(CQT,0) CQT FROM
(SELECT SPLSCN_ITMCD,SUM(SPLSCN_QTY) SQT FROM SPLSCN_TBL WHERE SPLSCN_DOC=@DOCNO
GROUP BY SPLSCN_ITMCD) VSPLSCN
LEFT JOIN
(SELECT SCNDOCITM_ITMCD,SUM(SCNDOCITM_QTY) CQT FROM SCNDOCITM_TBL WHERE SCNDOCITM_DOCNO=@DOCNO
GROUP BY SCNDOCITM_ITMCD) VCONFORM ON SPLSCN_ITMCD=SCNDOCITM_ITMCD
order by SPLSCN_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_check_doc_progress]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_check_doc_progress]  @DOCNO VARCHAR(25)
as
SELECT SUM(SQT) RQT,ISNULL(SUM(CQT),0) CQT FROM
(SELECT SPLSCN_ITMCD,SUM(SPLSCN_QTY) SQT FROM SPLSCN_TBL WHERE SPLSCN_DOC=@DOCNO
GROUP BY SPLSCN_ITMCD) VSPLSCN
LEFT JOIN
(SELECT SCNDOCITM_ITMCD,SUM(SCNDOCITM_QTY) CQT FROM SCNDOCITM_TBL WHERE SCNDOCITM_DOCNO=@DOCNO
GROUP BY SCNDOCITM_ITMCD) VCONFORM ON SPLSCN_ITMCD=SCNDOCITM_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_conversion_test]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_conversion_test]
	@date varchar(10),
	@date2 varchar(10),
	@model varchar(20),
	@aju varchar(26)
as
SELECT DLV_ZNOMOR_AJU
, DLV_ID
	, SER_ITMID
	, SERD2_ITMCD
	, PPSN1_BOMRV
	, SUM(PARTQT) RMQT
	, SUM(DLV_QTY) DLVQT
	, PER
	, RTRIM(MITM_ITMD1) PARTDESCRIPTION
	, MITMGRP_ITMCD
FROM (
	SELECT SER_ITMID
		, SERD2_ITMCD
		, SUM(SERD2_QTY) / DLV_QTY PER
		, PPSN1_BOMRV
		, DLV_SER
		, DLV_QTY
		, SUM(SERD2_QTY) PARTQT
		, DLV_ZNOMOR_AJU
		, DLV_ID
	FROM DLV_TBL
		LEFT JOIN SER_TBL ON DLV_SER = SER_ID
		LEFT JOIN SERD2_TBL ON DLV_SER = SERD2_SER
		LEFT JOIN (
		SELECT PPSN1_WONO
			, PPSN1_BOMRV
		FROM XPPSN1
		GROUP BY PPSN1_WONO
			,PPSN1_BOMRV
		) VPSN ON SER_DOC = PPSN1_WONO
	WHERE DLV_BCTYPE = '25'
		AND DLV_BCDATE between @date and @date2
		AND DLV_ZNOMOR_AJU LIKE concat('%',@aju,'%')
		AND SER_ITMID LIKE concat('%',@model,'%')
	GROUP BY SER_ITMID
		,SERD2_ITMCD		
		,PPSN1_BOMRV
		,DLV_SER
		,DLV_QTY
		,DLV_ZNOMOR_AJU
		,DLV_ID
	) VV1
	LEFT JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD
	LEFT JOIN MITMGRP_TBL ON MITM_ITMCD=MITMGRP_ITMCD_GRD
GROUP BY SER_ITMID
	,SERD2_ITMCD
	,PPSN1_BOMRV
	,DLV_ZNOMOR_AJU
	,PER
	,MITM_ITMD1
	,MITMGRP_ITMCD
	,DLV_ID
order by SER_ITMID
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_conversion_test_by_do]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[wms_sp_conversion_test_by_do]
	@doc varchar(26)
as
SELECT DLV_ZNOMOR_AJU
, DLV_ID
	, SER_ITMID
	, SERD2_ITMCD
	, PPSN1_BOMRV
	, SUM(PARTQT) RMQT
	, SUM(DLV_QTY) DLVQT
	, PER
	, RTRIM(MITM_ITMD1) PARTDESCRIPTION
	, RTRIM(MITM_STKUOM) PART_UOM
	, MITMGRP_ITMCD
	, SER_ITMNM
	, SER_ITM_HSCODE
	, SER_ITM_UOM
FROM (
	SELECT SER_ITMID
		, SERD2_ITMCD
		, SUM(SERD2_QTY) / DLV_QTY PER
		, PPSN1_BOMRV
		, DLV_SER
		, DLV_QTY
		, SUM(SERD2_QTY) PARTQT
		, DLV_ZNOMOR_AJU
		, DLV_ID
		, RTRIM(MITM_ITMD1) SER_ITMNM
		, RTRIM(MITM_HSCD) SER_ITM_HSCODE
		, RTRIM(MITM_STKUOM) SER_ITM_UOM
	FROM DLV_TBL
		LEFT JOIN SER_TBL ON DLV_SER = SER_ID
		LEFT JOIN MITM_TBL ON SER_ITMID=MITM_ITMCD 
		LEFT JOIN SERD2_TBL ON DLV_SER = SERD2_SER
		LEFT JOIN (
		SELECT PPSN1_WONO
			, PPSN1_BOMRV
		FROM XPPSN1
		GROUP BY PPSN1_WONO
			,PPSN1_BOMRV
		) VPSN ON SER_DOC = PPSN1_WONO
	WHERE 
		DLV_ID=@doc
	GROUP BY SER_ITMID
		,SERD2_ITMCD		
		,PPSN1_BOMRV
		,DLV_SER
		,DLV_QTY
		,DLV_ZNOMOR_AJU
		,DLV_ID
		,MITM_ITMD1
		,MITM_HSCD
		,MITM_STKUOM
	) VV1
	LEFT JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD
	LEFT JOIN MITMGRP_TBL ON MITM_ITMCD=MITMGRP_ITMCD_GRD
GROUP BY SER_ITMID
	,SERD2_ITMCD
	,PPSN1_BOMRV
	,DLV_ZNOMOR_AJU
	,PER
	,MITM_ITMD1
	,MITM_STKUOM
	,MITMGRP_ITMCD
	,DLV_ID
	,SER_ITMNM
	,SER_ITM_HSCODE
	,SER_ITM_UOM
order by SER_ITMID
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_DO_RM_rtn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_DO_RM_rtn]
@doc varchar(50)
as
SELECT DLVRMDOC_ITMID
	,DLVRMDOC_AJU
	,DLVRMDOC_NOPEN
	,sum(DLVRMDOC_ITMQT) DLVRMDOC_ITMQT
	,RTRIM(MITM_ITMD1) MITM_ITMD1
	,RTRIM(ISNULL(DLV_ITMD1,'')) DLV_ITMD1
	,RTRIM(MITM_SPTNO) MITM_SPTNO
	,DLV_ITMSPTNO
	,DLV_DATE
	,MDEL_ZNAMA
	,DLV_DSCRPTN
	,DLV_BCTYPE
	,DLV_INVNO
	,DLV_NOPEN
	,MDEL_ADDRCUSTOMS
	,RTRIM(MITM_STKUOM) MITM_STKUOM
	,RTRIM(DLVRMDOC_DO) DLVRMDOC_DO
	,ISNULL(MAX(MITM_ITMCDCUS), '') MITM_ITMCDCUS
	,DLVRMDOC_TYPE
FROM DLVRMDOC_TBL
LEFT JOIN (
	SELECT DLV_ID
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_DATE
		,ISNULL(DLV_INVNO, '') DLV_INVNO
		,ISNULL(DLV_SMTINVNO, '') DLV_SMTINVNO
		,RTRIM(MSUP_SUPCD) MCUS_CURCD
		,DLV_INVDT
		,isnull(DLV_NOPEN, '') DLV_NOPEN
		,ISNULL(MITMGRP_ITMCD,DLV_ITMCD) DLV_ITMCD
		,DLV_ITMD1
		,RTRIM(DLV_ITMSPTNO) DLV_ITMSPTNO
		,max(DLV_DSCRPTN) DLV_DSCRPTN
		,max(DLV_BCTYPE) DLV_BCTYPE
	FROM DLV_TBL
	LEFT JOIN MDEL_TBL ON DLV_CONSIGN = MDEL_DELCD
	LEFT JOIN MITMGRP_TBL ON DLV_ITMCD=MITMGRP_ITMCD_GRD
	LEFT JOIN v_supplier_customer_union ON DLV_CUSTCD = MSUP_SUPCD
	GROUP BY DLV_ID
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_DATE
		,DLV_INVNO
		,DLV_SMTINVNO
		,MSUP_SUPCR
		,DLV_INVDT
		,DLV_NOPEN
		,DLV_ITMCD
		,DLV_ITMD1
		,DLV_ITMSPTNO
		,MSUP_SUPCD
		,MITMGRP_ITMCD
	) VH ON DLVRMDOC_ITMID = DLV_ITMCD
	AND DLVRMDOC_TXID = DLV_ID
LEFT JOIN MITM_TBL ON DLVRMDOC_ITMID = MITM_ITMCD
WHERE DLVRMDOC_TXID = @doc
GROUP BY DLVRMDOC_ITMID
	,DLVRMDOC_AJU
	,DLVRMDOC_NOPEN
	,MITM_ITMD1
	,DLV_ITMD1
	,MITM_SPTNO
	,DLV_ITMSPTNO
	,DLV_DATE
	,MDEL_ZNAMA
	,DLV_DSCRPTN
	,DLV_INVNO
	,DLV_BCTYPE
	,DLV_NOPEN
	,MDEL_ADDRCUSTOMS
	,MITM_STKUOM
	,DLVRMDOC_DO
	,DLVRMDOC_TYPE
ORDER BY DLVRMDOC_ITMID
	,DLVRMDOC_AJU
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_fg]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_fg] 
 @PASSYCODE VARCHAR(50),
 @PJOB VARCHAR(50)
AS

SELECT MSPPMDLCD
	,MSPP_BOMPN
	,MSPP_SUBPN
	,MAX(MSPP_ACTIVE) MSPP_ACTIVE
	,MAX(XSUBNAME) XSUBNAME
FROM (
	SELECT @PASSYCODE MSPPMDLCD
		,ITMCDPRI MSPP_BOMPN
		,ITMCDALT MSPP_SUBPN
		,'Y' MSPP_ACTIVE
		,ISNULL(SUPNMALT, '') XSUBNAME
	FROM ENG_COMMPRT_LST
	WHERE ITMCDPRI IN (
			SELECT PIS3_MPART
			FROM XPIS3
			WHERE PIS3_WONO = @PJOB
			GROUP BY PIS3_MPART
			)
	
	UNION
	
	SELECT A.*
		,RTRIM(XSUB.MITM_SPTNO) XSUBNAME
	FROM XMSPP_HIS A
	LEFT JOIN MITM_TBL XSUB ON MSPP_SUBPN = XSUB.MITM_ITMCD
	WHERE MSPP_MDLCD = @PASSYCODE
	
	UNION
	
	SELECT @PASSYCODE MSPPMDLCD
		,RTRIM(MSIM_BOMPN) MSPP_BOMPN
		,RTRIM(MSIM_SUBPN) MSPP_SUBPN
		,'Y' MSPP_ACTIVE
		,'' XSUBNAME
	FROM XMSIM_TBL
	WHERE MSIM_MDLCD = @PASSYCODE
		AND MSIM_BOMPN != MSIM_SUBPN
	GROUP BY MSIM_MDLCD
		,MSIM_BOMPN
		,MSIM_SUBPN

	UNION

	SELECT @PASSYCODE MSPPMDLCD
	,RTRIM(MBOM_BOMPN) MSPP_BOMPN
	,RTRIM(MBOMD2H_OBOMPN) MSPP_SUBPN
	,'Y' MSPP_ACTIVE
	,'' XSUBNAME
	FROM XMBOM
	LEFT JOIN XMBOMD2H_TBL ON MBOM_RQRLSNO = MBOMD2H_RQRLSNO
		AND MBOM_LINE = MBOMD2H_LINE
	WHERE MBOM_MDLCD = @PASSYCODE
		AND ISNULL(MBOMD2H_OBOMPN, '') != ''
		AND MBOM_BOMPN != ISNULL(MBOMD2H_OBOMPN, '')
	GROUP BY MBOM_MDLCD
		,MBOM_BOMPN
		,MBOMD2H_OBOMPN
	) V1
WHERE MSPP_BOMPN != MSPP_SUBPN
GROUP BY MSPPMDLCD
	,MSPP_BOMPN
	,MSPP_SUBPN
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_fgstock]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_fgstock]
@BG VARCHAR(15),
@DATESEL DATE
AS
SELECT V1.*,RTRIM(MITM_ITMD1) ITMD1 FROM
(
	SELECT ITH_ITMCD
	,ISNULL(SUM(CASE WHEN ITH_WH='ARPRD1' THEN ITH_QTY END),0) LOC_PRD
	,ISNULL(SUM(CASE WHEN ITH_WH='ARQA1' THEN ITH_QTY END),0) LOC_QC
	,ISNULL(SUM(CASE WHEN ITH_WH='AFWH3' THEN ITH_QTY END),0) LOC_AFWH3
	,ISNULL(SUM(CASE WHEN ITH_WH='ARSHP' THEN ITH_QTY END),0) LOC_ARSHP
	,ISNULL(SUM(CASE WHEN ITH_WH='QAFG' THEN ITH_QTY END),0) LOC_QAFG
	,ISNULL(SUM(CASE WHEN ITH_WH='AFQART' THEN ITH_QTY END),0) LOC_AFQART
	,ISNULL(SUM(CASE WHEN ITH_WH='AFQART2' THEN ITH_QTY END),0) LOC_AFQART2
	,ISNULL(SUM(CASE WHEN ITH_WH='NFWH4RT' THEN ITH_QTY END),0) LOC_NFWH4RT
	,ISNULL(SUM(CASE WHEN ITH_WH='AFWH3RT' THEN ITH_QTY END),0) LOC_AFWH3RT
	,ISNULL(SUM(CASE WHEN ITH_WH='ARSHPRTN' THEN ITH_QTY END),0) LOC_ARSHPRTN
	,ISNULL(SUM(CASE WHEN ITH_WH='ARSHPRTN2' THEN ITH_QTY END),0) LOC_ARSHPRTN2
	FROM v_ith_tblc
	LEFT JOIN SER_TBL ON ITH_SER=SER_ID
	WHERE ITH_WH IN ('ARPRD1','ARQA1','AFWH3','ARSHP','QAFG','AFQART','AFQART2','NFWH4RT','AFWH3RT','ARSHPRTN','ARSHPRTN2')
	AND ITH_ITMCD IN (
		select SER_ITMID SSO2_MDLCD from SER_TBL where SER_BSGRP=@BG
		group by SER_ITMID
		)
	AND ITH_DATEC<=@DATESEL
	AND ISNULL(SER_RMRK,'') NOT LIKE '%SCRAP%'
	AND ISNULL(SER_RMRK,'') not like '%sample NG%'
	GROUP BY ITH_ITMCD
) V1
LEFT JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_get_CLS_Job]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE PROCEDURE [dbo].[WMS_sp_get_CLS_Job]
(
@PSN   [varchar](50) ,
@QTY   [varchar](50) ,
@mdl [varchar](50),
@bom [varchar](50),
@prd [varchar](50),
@all [varchar](50),
@cat [varchar](50) = 'OK'
)


AS
BEGIN
	SET NOCOUNT ON;


if (@all = 'mbla')	
BEGIN

select MBLA_ITMCD as [Item],isnull(SUM(qty),0) as [Scan],isnull(cout,0) as [Output], (ISNULL(SUM(QTY),0) - ISNULL(COUT,0)) AS [MINUS]
from VCIMS_MBLA_TBL left join
(select RTRIM(swmp_itmcd) as [Item_code],swmp_qty as [qty],SWMP_UNQ as [uniq],SWMP_PSNNO as [Psn_no],SWMP_LOTNO from WMS_SWMP_HIS
where SWMP_PSNNO = @PSN and SWMP_REMARK =  @cat and swmp_act = '1' group by SWMP_ITMCD,SWMP_QTY,SWMP_UNQ,SWMP_PSNNO,SWMP_LOTNO
UNION
select RTRIM(SWPS_nITMCD) as [Item_code],nqty as [qty],swps_nunq as [uniq],swps_psnno as [Psn_no],SWPS_NLOTNO  from WMS_SWPS_HIS
where SWPS_PSNNO =@PSN and SWPS_REMARK =  @cat  group by SWPS_NITMCD,SWPS_NUNQ,NQTY,SWPS_NLOTNO,SWPS_PSNNO) as rcv
on MBLA_ITMCD = rcv.Item_code
left join
(select isnull(sum(swmp_cls),0) as cout,SWMP_ITMCD from WMS_SWMP_HIS
where SWMP_PSNNO = @PSN and SWMP_REMARK =  @cat and swmp_act = '1' group by SWMP_ITMCD) as kel
on MBLA_ITMCD = kel.SWMP_ITMCD 
where MBLA_MDLCD = @mdl and MBLA_BOMRV = @bom and MBLA_PROCD = @prd
group by MBLA_ITMCD,KEL.cout
having  (ISNULL(SUM(QTY),0) - ISNULL(COUT,0)) < '0' order by MBLA_ITMCD


END

if (@all= 'outstd')
BEGIN

select splscn_itmcd as [Item],isnull(SUM(qty),0) as [Scan],isnull(cout,0) as [Output], (ISNULL(SUM(QTY),0) - ISNULL(COUT,0)) AS [MINUS]
from splscn_tbl left join
(select RTRIM(swmp_itmcd) as [Item_code],swmp_qty as [qty],SWMP_UNQ as [uniq],SWMP_PSNNO as [Psn_no],SWMP_LOTNO from WMS_SWMP_HIS
where SWMP_PSNNO = @PSN and SWMP_REMARK = @cat and swmp_act = '1' group by SWMP_ITMCD,SWMP_QTY,SWMP_UNQ,SWMP_PSNNO,SWMP_LOTNO
UNION
select RTRIM(SWPS_nITMCD) as [Item_code],nqty as [qty],swps_nunq as [uniq],swps_psnno as [Psn_no],SWPS_NLOTNO  from WMS_SWPS_HIS
where SWPS_PSNNO = @PSN and SWPS_REMARK = @cat  group by SWPS_NITMCD,SWPS_NUNQ,NQTY,SWPS_NLOTNO,SWPS_PSNNO) as rcv
on SPLSCN_ITMCD = rcv.Item_code
left join
(select isnull(sum(swmp_cls),0) as cout,SWMP_ITMCD from WMS_SWMP_HIS
where SWMP_PSNNO = @PSN and SWMP_REMARK = @cat and swmp_act = '1' group by SWMP_ITMCD) as kel
on splscn_itmcd = kel.SWMP_ITMCD 
where SPLSCN_DOC = @PSN and SPLSCN_PROCD = @prd
group by splscn_ITMCD,SPLSCN_UNQCODE, kel.cout
having  (ISNULL(SUM(QTY),0) - ISNULL(COUT,0)) < '0' order by splscn_ITMCD


END

if (@all = 'remqt')
begin

select SWMP_MAINITMCD as [Item],isnull(SUM(qty),0) as [Scan],isnull(cout,0) as cls,isnull(nuse,0) as pemakaian ,(isnull(cout,0) * isnull(nuse,0)) as [Output], (ISNULL(SUM(QTY),0) - (ISNULL(COUT,0) * isnull(nuse,0))) AS [MINUS]
from WMS_SWMP_HIS left join
(select RTRIM(SWMP_MAINITMCD) as [Item_code],swmp_qty as [qty],SWMP_UNQ as [uniq],SWMP_PSNNO as [Psn_no],SWMP_LOTNO from WMS_SWMP_HIS
where SWMP_REMARK = @cat  and SWMP_SPID = @PSN and swmp_act = '1' group by SWMP_MAINITMCD,SWMP_QTY,SWMP_UNQ,SWMP_PSNNO,SWMP_LOTNO
UNION
select RTRIM(SWPS_MAINITMCD) as [Item_code],nqty as [qty],swps_nunq as [uniq],swps_psnno as [Psn_no],SWPS_NLOTNO  from WMS_SWPS_HIS
where SWPS_REMARK = @cat  and SWPS_SPID = @PSN group by SWPS_MAINITMCD,SWPS_NUNQ,NQTY,SWPS_NLOTNO,SWPS_PSNNO) as rcv
on SWMP_MAINITMCD = rcv.Item_code
left join
(select distinct isnull(swmp_cls,0) as cout,SWMP_MAINITMCD as [itm2] from WMS_SWMP_HIS
where SWMP_REMARK = @cat  and SWMP_SPID = @PSN and swmp_act = '1'  group by SWMP_MAINITMCD,SWMP_CLS) as kel
on SWMP_MAINITMCD = kel.itm2
left join
(select sum(mbom_qty) as [nuse], MBOM_ITMCD from VCIMS_MBOM_TBL 
where MBOM_MDLCD = @mdl and MBOM_BOMRV = @bom and MBOM_PROCD = @prd group by MBOM_ITMCD) as pem
on SWMP_MAINITMCD = MBOM_ITMCD
where SWMP_REMARK = 'OK'  and SWMP_SPID = @PSN
group by SWMP_MAINITMCD,KEL.cout,pem.nuse

end

END




GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_get_CLS_JobNo]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE PROCEDURE [dbo].[WMS_sp_get_CLS_JobNo]
(
@PSN   [varchar](50) ,
@QTY   [varchar](50) ,
@mdl [varchar](50),
@bom [varchar](50),
@prd [varchar](50),
@sp [varchar](50),
@all [varchar](50),
@cat [varchar](50) = 'OK'
)


AS
BEGIN
	SET NOCOUNT ON;


if (@all = 'mbla')	
BEGIN

select SWMP_MAINITMCD as [Item],isnull(SUM(qty),0) as [Scan],(isnull(cout,0) * isnull(nuse,0)) as [Output], (ISNULL(SUM(QTY),0) - (ISNULL(COUT,0) * isnull(nuse,0))) AS [MINUS]
from WMS_SWMP_HIS left join
(select RTRIM(SWMP_MAINITMCD) as [Item_code],swmp_qty as [qty],SWMP_UNQ as [uniq],SWMP_PSNNO as [Psn_no],SWMP_LOTNO from WMS_SWMP_HIS
where SWMP_PSNNO = @psn and SWMP_REMARK =  @cat and swmp_act = '1' and SWMP_MDLCD = @mdl and SWMP_BOMRV = @bom group by SWMP_MAINITMCD,SWMP_QTY,SWMP_UNQ,SWMP_PSNNO,SWMP_LOTNO
UNION
select RTRIM(SWPS_MAINITMCD) as [Item_code],nqty as [qty],swps_nunq as [uniq],swps_psnno as [Psn_no],SWPS_NLOTNO  from WMS_SWPS_HIS
where SWPS_PSNNO =@psn and SWPS_REMARK =  @cat and SWPS_MDLCD = @mdl and SWPS_BOMRV = @bom  group by SWPS_MAINITMCD,SWPS_NUNQ,NQTY,SWPS_NLOTNO,SWPS_PSNNO) as rcv
on SWMP_MAINITMCD = rcv.Item_code
left join
(select distinct(isnull(sum(swmp_cls),0)) as cout,SWMP_MAINITMCD as [itm2] from WMS_SWMP_HIS
where SWMP_PSNNO = @PSN and SWMP_REMARK =  @cat and swmp_act = '1' and SWMP_PROCD = @prd and SWMP_MDLCD = @mdl and SWMP_BOMRV = @bom group by SWMP_MAINITMCD) as kel
on SWMP_MAINITMCD = kel.itm2
left join
(select sum(mbom_qty) as [nuse], MBOM_ITMCD from VCIMS_MBOM_TBL 
where MBOM_MDLCD = @mdl and MBOM_BOMRV = @bom and MBOM_PROCD = @prd group by MBOM_ITMCD) as pem
on SWMP_MAINITMCD = MBOM_ITMCD
where SWMP_MDLCD = @mdl and SWMP_BOMRV = @bom and SWMP_PROCD = @prd and SWMP_REMARK = @cat and SWMP_PSNNO = @psn and SWMP_SPID = @sp
group by SWMP_MAINITMCD,KEL.cout,pem.nuse
having  (ISNULL(SUM(QTY),0) - (ISNULL(COUT,0) * isnull(nuse,0))) < '0' order by SWMP_MAINITMCD


END

if (@all= 'outstd')
BEGIN

select splscn_itmcd as [Item],isnull(SUM(qty),0) as [Scan],(isnull(cout,0) * isnull(nuse,0)) as [Output], (ISNULL(SUM(QTY),0) - (isnull(cout,0) * isnull(nuse,0))) AS [MINUS]
from splscn_tbl left join
(select RTRIM(SWMP_MAINITMCD) as [Item_code],swmp_qty as [qty],SWMP_UNQ as [uniq],SWMP_PSNNO as [Psn_no],SWMP_LOTNO from WMS_SWMP_HIS
where SWMP_PSNNO = @PSN and SWMP_REMARK = @cat and swmp_act = '1' group by SWMP_MAINITMCD,SWMP_QTY,SWMP_UNQ,SWMP_PSNNO,SWMP_LOTNO
UNION
select RTRIM(SWPS_MAINITMCD) as [Item_code],nqty as [qty],swps_nunq as [uniq],swps_psnno as [Psn_no],SWPS_NLOTNO  from WMS_SWPS_HIS
where SWPS_PSNNO = @PSN and SWPS_REMARK = @cat  group by SWPS_MAINITMCD,SWPS_NUNQ,NQTY,SWPS_NLOTNO,SWPS_PSNNO) as rcv
on SPLSCN_ITMCD = rcv.Item_code
left join
(select isnull(sum(swmp_cls),0) as cout,SWMP_MAINITMCD from WMS_SWMP_HIS
where SWMP_PSNNO = @PSN and SWMP_REMARK = @cat and swmp_act = '1' group by SWMP_MAINITMCD) as kel
on splscn_itmcd = kel.SWMP_MAINITMCD 
left join
(select sum(mbom_qty) as [nuse], MBOM_ITMCD from VCIMS_MBOM_TBL 
where MBOM_MDLCD = @mdl and MBOM_BOMRV = @bom and MBOM_PROCD = @prd group by MBOM_ITMCD) as pem
on SPLSCN_ITMCD = MBOM_ITMCD
where SPLSCN_DOC = @PSN and SPLSCN_PROCD = @prd
group by splscn_ITMCD, SPLSCN_UNQCODE,kel.cout,pem.nuse
having  (ISNULL(SUM(QTY),0) - (isnull(cout,0) * isnull(nuse,0))) < '0' order by splscn_ITMCD


END



END




GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_get_dt]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE PROCEDURE [dbo].[WMS_sp_get_dt]
(
@mdlcd   [varchar](50) ,
@bomrv [varchar](50) ,
@lineno [varchar](50),
@procd [varchar](50),
@itmcd  [varchar](50),
@psn [Varchar](100),
@mc  [varchar](50),
@mcz  [varchar](50),
@yy [VARCHAR](100) ,
@status [varchar](50)
)


AS
BEGIN
	SET NOCOUNT ON;

	if @status = 'NPCB'
	BEGIN
SELECT * FROM
(SELECT MBLA_MC,MBLA_MCZ,MBLA_ITMCD FROM VCIMS_MBLA_TBL A WHERE A.MBLA_MDLCD=@mdlcd and MBLA_PROCD = @procd and MBLA_LINENO = @lineno and MBLA_BOMRV = @bomrv 
GROUP BY MBLA_MC,MBLA_MCZ,MBLA_ITMCD,MBLA_MC, MBLA_MCZ) V1
LEFT JOIN (
SELECT B.Trace_Mc,B.Trace_mcz,B.Trace_mainItmcd FROM WMS_Trace_Prep B where B.Trace_PSNNo = @psn and B.Trace_Mdlcd = @mdlcd AND B.Trace_LineNo = @lineno and B.Trace_Procd = @procd
GROUP BY B.Trace_Mc,B.Trace_mcz,B.Trace_mainItmcd
) V2 ON  mbla_itmcd =Trace_mainItmcd and MBLA_MCZ = Trace_mcz
where trace_mc is null order by mbla_mc,MBLA_MCZ asc
END

else if @status='PCB'
BEGIN
SELECT * FROM
(SELECT MBLA_MC,MBLA_MCZ,MBLA_ITMCD FROM VCIMS_MBLA_TBL A WHERE A.MBLA_MDLCD=@mdlcd and MBLA_PROCD = @procd and MBLA_LINENO = @lineno and MBLA_BOMRV = @bomrv
GROUP BY MBLA_MC,MBLA_MCZ,MBLA_ITMCD) V1
LEFT JOIN (
SELECT B.Trace_Mc,B.Trace_mcz,B.Trace_mainItmcd FROM WMS_Trace_Prep B where B.Trace_PSNNo = @psn and B.Trace_Mdlcd = @mdlcd AND B.Trace_LineNo = @lineno and B.Trace_Procd = @procd
GROUP BY B.Trace_Mc,B.Trace_mcz,B.Trace_mainItmcd
) V2 ON  mbla_itmcd =Trace_mainItmcd and MBLA_MCZ = Trace_mcz
where trace_mc is null and MBLA_ITMCD not in(@itmcd) order by mbla_mc,MBLA_MCZ asc
END


else if @status='tbl'

BEGIN
SELECT * FROM
(SELECT MBLA_MC,MBLA_MCZ,MBLA_ITMCD FROM VCIMS_MBLA_TBL A WHERE A.MBLA_MDLCD=@mdlcd and MBLA_PROCD = @procd and MBLA_LINENO = @lineno and MBLA_BOMRV = @bomrv and mbla_mc = @mc and MBLA_MCZ like @mcz
GROUP BY MBLA_MC,MBLA_MCZ,MBLA_ITMCD )v1
LEFT JOIN (
SELECT B.sWMP_MC,B.sWMP_ITMCD,b.sWMP_MCZ FROM WMS_SWMP_HIS B where B.sWMP_mdlcd = @mdlcd and B.sWMP_PROCD = @procd AND B.sWMP_LINENO = @lineno AND b.SWMP_SPID = @yy and SWMP_Remark = 'OK'
GROUP BY B.sWMP_MC,B.sWMP_ITMCD,b.sWMP_MCZ
) V2 ON  MBLA_MC = sWMP_MC and mBLA_MCZ = sWMP_MCZ 
 where swmp_mc is null order by mbla_MC,MBLA_MCZ asc

 END


 else if @status='tblh'

BEGIN
SELECT * FROM
(SELECT MBLA_MC,MBLA_MCZ,MBLA_ITMCD FROM VCIMS_MBLA_TBL A WHERE A.MBLA_MDLCD=@mdlcd and MBLA_PROCD = @procd and MBLA_LINENO = @lineno and MBLA_BOMRV = @bomrv and mbla_mc = @mc
GROUP BY MBLA_MC,MBLA_MCZ,MBLA_ITMCD )v1
LEFT JOIN (
SELECT B.sWMP_MC,B.sWMP_ITMCD,b.sWMP_MCZ FROM WMS_SWMP_HIS B where B.sWMP_mdlcd = @mdlcd and B.sWMP_PROCD = @procd AND B.sWMP_LINENO = @lineno AND b.SWMP_SPID = @yy and SWMP_Remark = 'OK'
GROUP BY B.sWMP_MC,B.sWMP_ITMCD,b.sWMP_MCZ
) V2 ON  MBLA_MC = sWMP_MC and mBLA_MCZ = sWMP_MCZ 
 where swmp_mc is null order by mbla_MC,MBLA_MCZ asc

 END


 else if @status='dbl'

BEGIN
SELECT * FROM
(SELECT MBLA_MC,MBLA_MCZ,MBLA_ITMCD FROM VCIMS_MBLA_TBL A WHERE A.MBLA_MDLCD=@mdlcd and MBLA_PROCD = @procd and MBLA_LINENO = @lineno and MBLA_BOMRV = @bomrv and mbla_mc = @mc and MBLA_MCZ like @mcz
GROUP BY MBLA_MC,MBLA_MCZ,MBLA_ITMCD )v1
LEFT JOIN (
SELECT B.dblc_MC,B.dblc_ITMCD,b.dblc_MCZ FROM WMS_dblc_HIS B where B.dblc_mdlcd = @mdlcd and B.dblc_PROCD = @procd AND B.dblc_LINENO = @lineno AND b.dblc_SPID = @yy and dblc_Remark = 'OK'
GROUP BY B.dblc_MC,B.dblc_ITMCD,b.dblc_MCZ
) V2 ON  MBLA_MC = dblc_MC and mBLA_MCZ = dblc_MCZ 
 where dblc_mc is null order by mbla_MC,MBLA_MCZ asc

 END



END




GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_get_dtp]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE PROCEDURE [dbo].[WMS_sp_get_dtp]
(
@mdlcd   [varchar](50) ,
@bomrv [varchar](50) ,
@lineno [varchar](50),
@procd [varchar](50),
@itmcd  [varchar](50),
@psn [Varchar](100),
@mc  [varchar](50),
@mcz  [varchar](50),
@yy [VARCHAR](100) ,
@spid [varchar](100),
@status [varchar](50)
)


AS
BEGIN
	SET NOCOUNT ON;

	if @status = 'NPCB'
	BEGIN
SELECT * FROM
(SELECT MBLA_MC,MBLA_MCZ,MBLA_ITMCD FROM VCIMS_MBLA_TBL A WHERE A.MBLA_MDLCD=@mdlcd and MBLA_PROCD = @procd and MBLA_LINENO = @lineno and MBLA_BOMRV = @bomrv 
GROUP BY MBLA_MC,MBLA_MCZ,MBLA_ITMCD,MBLA_MC, MBLA_MCZ) V1
LEFT JOIN (
SELECT B.Trace_Mc,B.Trace_mcz,B.Trace_mainItmcd FROM WMS_Trace_Prep B where B.Trace_PSNNo = @psn and B.Trace_Mdlcd = @mdlcd AND B.Trace_LineNo = @lineno and B.Trace_Procd = @procd and trace_spid = @spid
GROUP BY B.Trace_Mc,B.Trace_mcz,B.Trace_mainItmcd
) V2 ON  mbla_itmcd =Trace_mainItmcd and MBLA_MCZ = Trace_mcz
where trace_mc is null order by mbla_mc,MBLA_MCZ asc
END

else if @status='PCB'
BEGIN
SELECT * FROM
(SELECT MBLA_MC,MBLA_MCZ,MBLA_ITMCD FROM VCIMS_MBLA_TBL A WHERE A.MBLA_MDLCD=@mdlcd and MBLA_PROCD = @procd and MBLA_LINENO = @lineno and MBLA_BOMRV = @bomrv
GROUP BY MBLA_MC,MBLA_MCZ,MBLA_ITMCD) V1
LEFT JOIN (
SELECT B.Trace_Mc,B.Trace_mcz,B.Trace_mainItmcd FROM WMS_Trace_Prep B where B.Trace_PSNNo = @psn and B.Trace_Mdlcd = @mdlcd AND B.Trace_LineNo = @lineno and B.Trace_Procd = @procd and trace_spid = @spid
GROUP BY B.Trace_Mc,B.Trace_mcz,B.Trace_mainItmcd
) V2 ON  mbla_itmcd =Trace_mainItmcd and MBLA_MCZ = Trace_mcz
where trace_mc is null and MBLA_ITMCD not in(@itmcd) order by mbla_mc,MBLA_MCZ asc
END




END




GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_get_ppsn2]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE PROCEDURE [dbo].[WMS_sp_get_ppsn2]
(
@status [varchar](50)
)


AS
BEGIN
	SET NOCOUNT ON;
	begin
	select @status = ppsn2_lupdt from smttrace.dbo.PPSN2_LOG order by PPSN2_LUPDT desc
	return @status

	insert into smttrace.dbo.PPSN2_LOG select * from XPPSN2_LOG where ppsn2_lupdt > @status and PPSN2_STATUS in ('Insert','delete') and PPSN2_BSGRP = 'PSI1PPZIEP'

	end

	

END




GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_get_SQ]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE PROCEDURE [dbo].[WMS_sp_get_SQ]
(
@mdlcd   [varchar](50),
@bomrv [varchar](50),
@procd [varchar](50)
)


AS
BEGIN
	SET NOCOUNT ON;

	
	BEGIN
SELECT distinct MBO2_SEQNO FROM VCIMS_MBO2_TBL
where MBO2_MDLCD = '221999300' and MBO2_BOMRV = '16.50' and MBO2_PROCD = 'SMT-B'
END


END




GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_get_sum_CLS]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE PROCEDURE [dbo].[WMS_sp_get_sum_CLS]
(
@PSN   [varchar](50) ,
@QTY   [varchar](50) ,
@cat [varchar](50) = 'OK'
)


AS
BEGIN
	SET NOCOUNT ON;


	
BEGIN
SELECT Item_code,sum(qty) qty,(sum(qty) - @QTY) as Mns FROM
(select RTRIM(swmp_itmcd) as [Item_code],swmp_qty as [qty],SWMP_UNQ as [uniq],SWMP_PSNNO as [Psn_no],SWMP_LOTNO from WMS_SWMP_HIS where SWMP_PSNNO = @PSN and SWMP_REMARK = @cat and swmp_act = '1'
UNION
select RTRIM(SWPS_nITMCD) as [Item_code],nqty as [qty],swps_nunq as [uniq],swps_psnno as [Psn_no],SWPS_LOTNO  from WMS_SWPS_HIS where swps_psnno = @PSN and swps_remark = @cat) V1
GROUP BY Item_code
having sum(qty) < @QTY order by Item_code asc


END



END




GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_get_sum_psn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE PROCEDURE [dbo].[WMS_sp_get_sum_psn]
(
@ScanPSN   [varchar](50) ,
@splscn_cat [varchar](50) ,
@splscn_line [varchar](50),
@splscn_fedr [varchar](50),
@scanItem  [varchar](50),
@OrderNo  [varchar](50),
@status   [varchar](30)
)


AS
BEGIN
	SET NOCOUNT ON;


IF (@status = 'spl')
BEGIN
SELECT SUM(SPL_QTYREQ) FROM SPL_TBL WHERE SPL_DOC=@ScanPSN AND SPL_ITMCD=@scanItem AND SPL_ORDERNO=@OrderNo and SPL_CAT=@splscn_cat AND SPL_FEDR=@splscn_fedr AND SPL_LINE=@splscn_line
END
ELSE IF (@status = 'ith')
BEGIN
SELECT SUM(SPLSCN_QTY) FROM SPLSCN_TBL WHERE SPLSCN_DOC=@ScanPSN AND SPLSCN_ITMCD=@scanItem AND SPLSCN_ORDERNO=@OrderNo and SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND SPLSCN_SAVED='1'
END
ELSE IF(@status = 'splscn')
BEGIN
SELECT SUM(SPLSCN_QTY) FROM SPLSCN_TBL WHERE SPLSCN_DOC=@ScanPSN AND SPLSCN_ITMCD=@scanItem AND SPLSCN_ORDERNO=@OrderNo AND SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND SPLSCN_SAVED IS NULL
END
ELSE IF(@status = 'cekith')
BEGIN
SELECT * FROM ITH_TBL WHERE ITH_ITMCD=@scanItem AND ITH_DATE=CAST(getdate() as date) AND ITH_DOC LIKE ''+@ScanPSN +'%' AND ITH_FORM='INC-PRD-RM'
END
else if (@status='updsplscn')
BEGIN
UPDATE SPLSCN_TBL SET SPLSCN_SAVED='1' WHERE SPLSCN_DOC=@ScanPSN  AND SPLSCN_ITMCD=@scanItem AND SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND SPLSCN_ORDERNO=@OrderNo 
END

else if (@status='checkcancel')
BEGIN
SELECT * FROM SPLSCN_TBL WHERE SPLSCN_DOC=@ScanPSN  AND SPLSCN_ITMCD=@scanItem AND SPLSCN_ORDERNO=@OrderNo 
AND SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND SPLSCN_SAVED IS NULL
END

else if (@status='deletecancel')
BEGIN
DELETE FROM SPLSCN_TBL WHERE SPLSCN_DOC=@ScanPSN  AND SPLSCN_ITMCD=@scanItem AND SPLSCN_ORDERNO=@OrderNo AND SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND SPLSCN_SAVED IS NULL

END

else if (@status='countitm')
BEGIN
SELECT A.SPLSCN_DOC,A.SPLSCN_ORDERNO,A.SPLSCN_ITMCD,SUM(A.SPLSCN_QTY) AS SPL_QTY , Z.SPL_QTYREQ FROM SPLSCN_TBL A
INNER JOIN (SELECT SPL_DOC,SPL_ORDERNO,SPL_ITMCD,SUM(SPL_QTYREQ) AS SPL_QTYREQ FROM SPL_TBL 
WHERE SPL_DOC=@ScanPSN AND SPL_CAT=@splscn_cat AND SPL_FEDR=@splscn_fedr AND SPL_LINE=@splscn_line
GROUP BY SPL_DOC,SPL_ORDERNO,SPL_ITMCD) AS Z ON  Z.SPL_DOC=a.SPLSCN_DOC AND Z.SPL_ORDERNO=a.SPLSCN_ORDERNO 
AND Z.SPL_ITMCD=a.SPLSCN_ITMCD 
where  A.SPLSCN_DOC=@ScanPSN and SPLSCN_CAT=@splscn_cat AND SPLSCN_FEDR=@splscn_fedr AND SPLSCN_LINE=@splscn_line AND A.SPLSCN_SAVED='1'
GROUP BY A.SPLSCN_DOC,A.SPLSCN_ORDERNO,A.SPLSCN_ITMCD,Z.SPL_QTYREQ
having sum(A.SPLSCN_QTY)>=Z.SPL_QTYREQ
END 
else if (@status='CountItmSPL')
BEGIN
SELECT SPL_DOC FROM SPL_TBL WHERE SPL_DOC=@ScanPSN AND SPL_CAT=@splscn_cat AND SPL_FEDR=@splscn_fedr AND SPL_LINE=@splscn_line  GROUP BY SPL_DOC,SPL_ORDERNO,SPL_ITMCD
END 
END




GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_get_sum_tbl]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





CREATE PROCEDURE [dbo].[WMS_sp_get_sum_tbl]
(
@mdlcd   [varchar](50) ,
@bomrv [varchar](50) ,
@lineno [varchar](50),
@procd [varchar](50),
@itmcd  [varchar](50),
@mc  [varchar](50),
@mcz  [varchar](50),
@cat [varchar](50) = 'PCB',
@status [varchar](50)
)


AS
BEGIN
	SET NOCOUNT ON;


	if @status = 'ttlitm'
BEGIN
select mbla_mdlcd,mbla_itmcd,mbla_mc,mbla_mcz from VCIMS_MBLA_TBL where mbla_mdlcd = @mdlcd and mbla_bomrv = @bomrv and mbla_lineno = @lineno and mbla_procd = @procd and mbla_mc = @mc and mbla_mcz like @mcz and MBLA_LOCCD not in (@cat) group by mbla_itmcd,mbla_mcz, mbla_mdlcd,mbla_bomrv, mbla_mc,mbla_procd
END

else if @status='gettbl'
BEGIN
select mbla_mc as [mc],mbla_mcz as [mcz] from VCIMS_MBLA_TBL where mbla_mdlcd = @mdlcd and mbla_bomrv = @bomrv and mbla_lineno = @lineno and mbla_procd = @procd and mbla_itmcd = @itmcd group by mbla_itmcd,mbla_mcz, mbla_mdlcd,mbla_bomrv, mbla_mc,mbla_procd
END

else if @status='ttlallitm'
BEGIN
select mbla_mdlcd,mbla_itmcd,mbla_mc,mbla_mcz from VCIMS_MBLA_TBL where mbla_mdlcd = @mdlcd and mbla_bomrv = @bomrv and mbla_lineno = @lineno and mbla_procd = @procd  and MBLA_LOCCD not in (@cat) group by mbla_itmcd,mbla_mcz, mbla_mdlcd,mbla_bomrv, mbla_mc,mbla_procd
END

else if @status = 'ttlitmcm'

BEGIN
select mbla_mdlcd,mbla_itmcd,mbla_mc,mbla_mcz from VCIMS_MBLA_TBL where mbla_mdlcd = @mdlcd and mbla_bomrv = @bomrv and mbla_lineno = @lineno and mbla_procd = @procd and mbla_mc = @mc and mbla_mcz like @mcz  group by mbla_itmcd,mbla_mcz, mbla_mdlcd,mbla_bomrv, mbla_mc,mbla_procd
END

else if @status = 'ttlitmch'

BEGIN
select mbla_mdlcd,mbla_itmcd,mbla_mc,mbla_mcz from VCIMS_MBLA_TBL where mbla_mdlcd = @mdlcd and mbla_bomrv = @bomrv and mbla_lineno = @lineno and mbla_procd = @procd and mbla_mc = @mc  group by mbla_itmcd,mbla_mcz, mbla_mdlcd,mbla_bomrv, mbla_mc,mbla_procd
END


else if @status='ttlallitmcm'
BEGIN
select mbla_mdlcd,mbla_itmcd,mbla_mc,mbla_mcz from VCIMS_MBLA_TBL where mbla_mdlcd = @mdlcd and mbla_bomrv = @bomrv and mbla_lineno = @lineno and mbla_procd = @procd  group by mbla_itmcd,mbla_mcz, mbla_mdlcd,mbla_bomrv, mbla_mc,mbla_procd
END

else if @status='ttlallitmch'
BEGIN
select mbla_mdlcd,mbla_itmcd,mbla_mc,mbla_mcz from VCIMS_MBLA_TBL where mbla_mdlcd = @mdlcd and mbla_bomrv = @bomrv and mbla_lineno = @lineno and mbla_procd = @procd  group by mbla_itmcd,mbla_mcz, mbla_mdlcd,mbla_bomrv, mbla_mc,mbla_procd
END


END




GO
/****** Object:  StoredProcedure [dbo].[wms_sp_getstock_ser_wh_rtn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_getstock_ser_wh_rtn]
@reffno varchar(25)
as
SELECT VSTOCK.*
	,ITH_LOC
FROM (
	SELECT ITH_SER
		,ITH_WH
		,SUM(ITH_QTY) ITH_QTY
		,MAX(ITH_LUPDT) ITH_LUPDT
	FROM ITH_TBL
	WHERE ITH_SER = @reffno
	GROUP BY ITH_SER
		,ITH_WH
	HAVING SUM(ITH_QTY) > 0
	) VSTOCK
LEFT JOIN ITH_TBL A ON VSTOCK.ITH_SER = A.ITH_SER
	AND VSTOCK.ITH_WH = A.ITH_WH
	AND VSTOCK.ITH_LUPDT = A.ITH_LUPDT
WHERE A.ITH_QTY > 0
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_invoice_rm_rtn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_invoice_rm_rtn]
@doc varchar(50)
as
SELECT VINV.*
	,MDEL_ZNAMA
	,MDEL_ADDRCUSTOMS
	,DLV_DATE
	,DLV_INVNO
	,DLV_SMTINVNO
	,MCUS_CURCD
	,DLV_INVDT
	,DLV_NOPEN
	,DLV_ITMD1
	,DLV_ITMSPTNO
	,DLV_BCTYPE
	,DLV_DSCRPTN
FROM (
	SELECT DLVRMDOC_TXID
		,RTRIM(DLVRMDOC_ITMID) DLVRMDOC_ITMID
		,RTRIM(MITM_ITMD1) MITM_ITMD1
		,DLVRMDOC_PRPRC
		,sum(DLVRMDOC_ITMQT) ITMQT
		,DLVRMDOC_PRPRC * SUM(DLVRMDOC_ITMQT) AMNT
		,RTRIM(MITM_STKUOM) MITM_STKUOM
		,DLVRMDOC_TYPE
		,DLVRMDOC_NOPEN
		,DLVRMDOC_AJU
		,RTRIM(DLVRMDOC_DO) DLVRMDOC_DO
		,MITM_ITMCDCUS
	FROM DLVRMDOC_TBL
	LEFT JOIN MITM_TBL ON DLVRMDOC_ITMID = MITM_ITMCD
	WHERE DLVRMDOC_TXID = @doc
	GROUP BY DLVRMDOC_ITMID
		,MITM_ITMD1
		,DLVRMDOC_PRPRC
		,DLVRMDOC_TXID
		,MITM_STKUOM
		,DLVRMDOC_TYPE
		,DLVRMDOC_NOPEN
		,DLVRMDOC_AJU
		,DLVRMDOC_DO
		,MITM_ITMCDCUS
	) VINV
LEFT JOIN (
	SELECT DLV_ID
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_DATE
		,ISNULL(DLV_INVNO, '') DLV_INVNO
		,ISNULL(DLV_SMTINVNO, '') DLV_SMTINVNO
		,RTRIM(MCUS_CURCD) MCUS_CURCD
		,DLV_INVDT
		,isnull(DLV_NOPEN, '') DLV_NOPEN
		,ISNULL(MITMGRP_ITMCD, DLV_ITMCD) DLV_ITMCD
		,DLV_ITMD1
		,DLV_ITMSPTNO
		,max(DLV_BCTYPE) DLV_BCTYPE
		,max(DLV_DSCRPTN) DLV_DSCRPTN
	FROM DLV_TBL
	LEFT JOIN MDEL_TBL ON DLV_CONSIGN = MDEL_DELCD
	LEFT JOIN MITMGRP_TBL ON DLV_ITMCD = MITMGRP_ITMCD_GRD
	LEFT JOIN (
		SELECT MSUP_SUPCD MCUS_CUSCD
			,max(MSUP_SUPCR) MCUS_CURCD
		FROM v_supplier_customer_union
		GROUP BY MSUP_SUPCD
		) vcust ON DLV_CUSTCD = MCUS_CUSCD
	WHERE DLV_ID = @doc
	GROUP BY DLV_ID
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_DATE
		,DLV_INVNO
		,DLV_SMTINVNO
		,MCUS_CURCD
		,DLV_INVDT
		,DLV_NOPEN
		,DLV_ITMCD
		,DLV_ITMD1
		,DLV_ITMSPTNO
		,MITMGRP_ITMCD
	) VH ON DLVRMDOC_TXID = DLV_ID
	AND DLVRMDOC_ITMID = DLV_ITMCD
ORDER BY DLVRMDOC_ITMID
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_kka_mega_fg]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[wms_sp_kka_mega_fg] 
@DATEFROM AS VARCHAR(10),
@DATETO AS VARCHAR(10)
as
select VMEGA.*,ISNULL(INCQTY,0) INCQTY, ISNULL(ADJINCQTY,0) ADJINCQTY,ISNULL(DLVQTY,0) DLVQTY, ISNULL(ADJOUTQTY,0) ADJOUTQTY from
(SELECT RTRIM(FTRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE 0-FTRN_TRNQT END) B4QTY
	,RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
			FROM XFTRN_TBL
			LEFT JOIN XMITM_V ON FTRN_ITMCD=MITM_ITMCD 
			WHERE FTRN_ISUDT<@DATEFROM AND FTRN_LOCCD IN ('AFWH3','NFWH4','QAFG') 
			GROUP BY FTRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1
) VMEGA
LEFT JOIN (SELECT  RTRIM(FTRN_ITMCD) ITRN_ITMCD
	,SUM(CASE WHEN FTRN_DOCCD='GRN' THEN FTRN_TRNQT END) INCQTY
	,SUM(CASE WHEN FTRN_DOCCD='ADJ' AND FTRN_IOFLG = '1' THEN FTRN_TRNQT END) ADJINCQTY
	,SUM(CASE WHEN FTRN_DOCCD='SHP' THEN FTRN_TRNQT END) DLVQTY		
	,SUM(CASE WHEN FTRN_DOCCD='ADJ' AND FTRN_IOFLG != '1' THEN FTRN_TRNQT END) ADJOUTQTY
	,RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
			FROM XFTRN_TBL
			LEFT JOIN XMITM_V ON FTRN_ITMCD=MITM_ITMCD 
			WHERE (FTRN_ISUDT BETWEEN @DATEFROM AND @DATETO) AND FTRN_LOCCD IN ('AFWH3','NFWH4','QAFG')
			GROUP BY FTRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1
) VMEGAMONTH ON VMEGA.ITRN_ITMCD=VMEGAMONTH.ITRN_ITMCD
ORDER BY ITRN_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_kka_mega_fg_rtn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE procedure [dbo].[wms_sp_kka_mega_fg_rtn] 
@DATEFROM AS VARCHAR(10),
@DATETO AS VARCHAR(10)
as
select VMEGA.*,ISNULL(INCQTY,0) INCQTY, ISNULL(ADJINCQTY,0) ADJINCQTY,ISNULL(DLVQTY,0) DLVQTY, ISNULL(ADJOUTQTY,0) ADJOUTQTY from
(SELECT RTRIM(FTRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE 0-FTRN_TRNQT END) B4QTY
	,RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
			FROM XFTRN_TBL
			LEFT JOIN XMITM_V ON FTRN_ITMCD=MITM_ITMCD 
			WHERE FTRN_ISUDT<@DATEFROM AND FTRN_LOCCD IN ('AFWH3RT','NFWH4RT','NFWH9SC') 
			GROUP BY FTRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1
) VMEGA
LEFT JOIN (SELECT  RTRIM(FTRN_ITMCD) ITRN_ITMCD
	,SUM(CASE WHEN FTRN_DOCCD='GRN' THEN FTRN_TRNQT END) INCQTY
	,SUM(CASE WHEN FTRN_DOCCD='ADJ' AND FTRN_IOFLG = '1' THEN FTRN_TRNQT END) ADJINCQTY
	,SUM(CASE WHEN FTRN_DOCCD='SHP' THEN FTRN_TRNQT END) DLVQTY		
	,SUM(CASE WHEN FTRN_DOCCD='ADJ' AND FTRN_IOFLG != '1' THEN FTRN_TRNQT END) ADJOUTQTY
	,RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
			FROM XFTRN_TBL
			LEFT JOIN XMITM_V ON FTRN_ITMCD=MITM_ITMCD 
			WHERE (FTRN_ISUDT BETWEEN @DATEFROM AND @DATETO) AND FTRN_LOCCD IN ('AFWH3RT','NFWH4RT')
			GROUP BY FTRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1
) VMEGAMONTH ON VMEGA.ITRN_ITMCD=VMEGAMONTH.ITRN_ITMCD
ORDER BY ITRN_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_kka_mega_rm]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[wms_sp_kka_mega_rm]
@DATEFROM AS VARCHAR(10),
@DATETO AS VARCHAR(10)
as
select VMEGA.*,ISNULL(INCQTY,0) INCQTY,ISNULL(PRDINCQTY,0) PRDINCQTY, ISNULL(ADJINCQTY,0) ADJINCQTY,ISNULL(DLVQTY,0) DLVQTY, ISNULL(ADJOUTQTY,0) ADJOUTQTY from
(SELECT RTRIM(ITRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT ELSE -1*ITRN_TRNQT END) B4QTY
	,RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
			FROM XITRN_TBL
			LEFT JOIN XMITM_V ON ITRN_ITMCD=MITM_ITMCD 
			WHERE ITRN_ISUDT<@DATEFROM AND ITRN_LOCCD IN ('ARWH1','ARWH2','NRWH2','QA','ARWH0PD') 
			GROUP BY ITRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1
) VMEGA
LEFT JOIN (SELECT  RTRIM(ITRN_ITMCD) ITRN_ITMCD
	,SUM(CASE WHEN ITRN_DOCCD='GRN' THEN ITRN_TRNQT END) INCQTY
	,SUM(CASE WHEN ITRN_DOCCD='TRF' AND ITRN_IOFLG = '1' AND ITRN_REFNO1!='QA' THEN ITRN_TRNQT END) PRDINCQTY
	,SUM(CASE WHEN ITRN_DOCCD='ADJ' AND ITRN_IOFLG = '1' THEN ITRN_TRNQT END) ADJINCQTY
	,SUM(CASE WHEN ITRN_DOCCD='TRF' AND ITRN_IOFLG != '1' AND ITRN_REFNO1!='QA' AND ITRN_LOCCD!='QA' THEN ITRN_TRNQT END) DLVQTY		
	,SUM(CASE WHEN ITRN_DOCCD='ADJ' AND ITRN_IOFLG != '1' THEN ITRN_TRNQT END) ADJOUTQTY
	,RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
			FROM XITRN_TBL
			LEFT JOIN XMITM_V ON ITRN_ITMCD=MITM_ITMCD 
			WHERE (ITRN_ISUDT BETWEEN @DATEFROM AND @DATETO) AND ITRN_LOCCD IN ('ARWH1','ARWH2','NRWH2','ARWH0PD')
			GROUP BY ITRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1
) VMEGAMONTH ON VMEGA.ITRN_ITMCD=VMEGAMONTH.ITRN_ITMCD
ORDER BY ITRN_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_NEWSaveToITH]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


Create PROCEDURE [dbo].[WMS_sp_NEWSaveToITH]
(
@ITH_ITMCD [varchar](50),
@ITH_DATE [DATETIME],
@ITH_FORM [varchar](50),
@ITH_DOC [varchar](50),
@ITH_QTY [decimal](12, 3),
@ITH_WH [varchar](50),
@ITH_SER [varchar](50),
@ITH_REMARK [varchar](50),
@ITH_LOC [varchar](50),
@ITH_LUPDT [varchar](50),
@ITH_USRID [varchar](50),
@status   [varchar](50)
)

		AS
BEGIN 
     SET NOCOUNT ON 

DECLARE @BG VARCHAR(50)
DECLARE @BOOKID VARCHAR(50)
IF (@status ='Insert')
	BEGIN
	if (@ITH_FORM='INC-PRD-RM')
		BEGIN	
			SET @BG = (SELECT TOP 1 RTRIM(SPL_BG) FROM SPL_TBL WHERE SPL_DOC=SUBSTRING(@ITH_DOC,1,19))		
			SET @ITH_WH = CASE 
				WHEN @BG='PSI1PPZIEP' THEN 'PLANT1'
				WHEN @BG='PSI2PPZADI' THEN 'PLANT2'
				WHEN @BG='PSI2PPZSTY' THEN 'PLANT2'
				WHEN @BG='PSI2PPZOMI' THEN 'PLANT2'
				WHEN @BG='PSI2PPZTDI' THEN 'PLANT2'
				WHEN @BG='PSI2PPZINS' THEN 'PLANT_NA'
				WHEN @BG='PSI2PPZOMC' THEN 'PLANT_NA'			
				WHEN @BG='PSI2PPZSSI' THEN 'PLANT_NA'			
				END
		INSERT INTO [ITH_TBL] 
		(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LOC,ITH_LUPDT,ITH_USRID)
		VALUES(@ITH_ITMCD,@ITH_LUPDT,@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK,
		@ITH_LOC,@ITH_LUPDT,@ITH_USRID)
		END
	
	END





END
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_outstanding_psn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_outstanding_psn]
@PSNNUM VARCHAR(50)
as
SELECT VREQ.*,ISNULL(TTLSCN,0) TTLSCN,RTRIM(MITM_SPTNO) MITM_SPTNO,ISNULL(TTLREFF,0)  TTLREFF FROM
(select SPL_ITMCD,sum(SPL_QTYREQ) TTLREQ,SPL_CAT,SPL_ORDERNO,SPL_LINE,SPL_FEDR from SPL_TBL where SPL_DOC=@PSNNUM
group by SPL_ITMCD,SPL_CAT,SPL_ORDERNO,SPL_LINE,SPL_FEDR) VREQ
LEFT JOIN 
(SELECT SPLSCN_ITMCD,SUM(SPLSCN_QTY) TTLSCN,SPLSCN_CAT,SPLSCN_ORDERNO,SPLSCN_LINE,SPLSCN_FEDR FROM SPLSCN_TBL WHERE SPLSCN_DOC=@PSNNUM
GROUP BY SPLSCN_ITMCD,SPLSCN_CAT,SPLSCN_ORDERNO,SPLSCN_LINE,SPLSCN_FEDR) VSCN ON SPL_ITMCD=SPLSCN_ITMCD
	AND SPL_CAT=SPLSCN_CAT AND SPL_ORDERNO=SPLSCN_ORDERNO AND SPL_LINE=SPLSCN_LINE AND SPL_FEDR=SPLSCN_FEDR
LEFT JOIN
(
SELECT SPLREFF_REQ_PART,SUM(SPLREFF_ACT_QTY) TTLREFF,SPLREFF_ITMCAT,SPLREFF_MCZ,SPLREFF_LINEPRD,SPLREFF_FEDR 
FROM SPLREFF_TBL WHERE SPLREFF_DOC=@PSNNUM
GROUP BY SPLREFF_REQ_PART,SPLREFF_ITMCAT,SPLREFF_MCZ,SPLREFF_LINEPRD,SPLREFF_FEDR
) VREFF ON SPL_ITMCD=SPLREFF_REQ_PART AND SPL_CAT=SPLREFF_ITMCAT AND SPL_ORDERNO=SPLREFF_MCZ AND SPL_LINE=SPLREFF_LINEPRD AND SPL_FEDR=SPLREFF_FEDR
LEFT JOIN MITM_TBL ON SPL_ITMCD=MITM_ITMCD 

WHERE ISNULL(TTLSCN,0)<TTLREQ
ORDER BY SPL_ORDERNO, SPL_ITMCD
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_packaging_rm_rtn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_packaging_rm_rtn]
@doc varchar(50)
as
SELECT DLV_PKG_TBL.*
	,RTRIM(MITM_ITMD1) MITM_ITMD1
	,MDEL_ZNAMA
	,MDEL_ADDRCUSTOMS
	,DLV_DATE
	,DLV_INVNO
	,DLV_SMTINVNO
	,MCUS_CURCD
	,DLV_INVDT
	,DLV_NOPEN
	,DLV_ITMD1
	,DLV_ITMSPTNO
	,RTRIM(MITM_SPTNO) MITM_SPTNO
	,ISNULL(MITM_ITMCDCUS, '-') MITM_ITMCDCUS
	,RTRIM(MITM_STKUOM) MITM_STKUOM
FROM DLV_PKG_TBL
LEFT JOIN MITM_TBL ON DLV_PKG_ITM = MITM_ITMCD
LEFT JOIN (
	SELECT DLV_ID
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_DATE
		,ISNULL(DLV_INVNO, '') DLV_INVNO
		,ISNULL(DLV_SMTINVNO, '') DLV_SMTINVNO
		,RTRIM(MCUS_CURCD) MCUS_CURCD
		,DLV_INVDT
		,isnull(DLV_NOPEN, '') DLV_NOPEN
		,ISNULL(MITMGRP_ITMCD,DLV_ITMCD) DLV_ITMCD
		,DLV_ITMD1
		,DLV_ITMSPTNO
	FROM DLV_TBL
	LEFT JOIN MDEL_TBL ON DLV_CONSIGN = MDEL_DELCD
	LEFT JOIN MCUS_TBL ON DLV_CUSTCD = MCUS_CUSCD
	LEFT JOIN MITMGRP_TBL ON DLV_ITMCD=MITMGRP_ITMCD_GRD
	GROUP BY DLV_ID
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_DATE
		,DLV_INVNO
		,DLV_SMTINVNO
		,MCUS_CURCD
		,DLV_INVDT
		,DLV_NOPEN
		,DLV_ITMCD
		,DLV_ITMD1
		,DLV_ITMSPTNO
		,MITMGRP_ITMCD
	) VH ON DLV_PKG_DOC = DLV_ID
	AND DLV_PKG_ITM = DLV_ITMCD
WHERE DLV_ID = @doc




GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_PSN_INSERTSCANSPL]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[WMS_sp_PSN_INSERTSCANSPL]
		(
		@SPLSCN_ID    [varchar](100),
@SPLSCN_DOC    [varchar](100),
@SPLSCN_CAT    [varchar](100),
@SPLSCN_LINE    [varchar](100),
@SPLSCN_FEDR    [varchar](100),
@SPLSCN_ORDERNO    [varchar](100),
@SPLSCN_ITMCD    [varchar](100),
@SPLSCN_LOTNO    [varchar](100),
@SPLSCN_QTY    [decimal](12,3),
@SPLSCN_LUPDT    [varchar](100),
@SPLSCN_USRID    [varchar](20),
@SPLSCN_MCN		[varchar](25),
@SPLSCN_PROCD	[varchar](25),
@SPLSCN_UNQCODE	[VARCHAR](25)

		
		)

		AS
BEGIN 
     SET NOCOUNT ON 

INSERT INTO [SPLSCN_TBL]
(
SPLSCN_ID,
SPLSCN_DOC,
SPLSCN_CAT,
SPLSCN_LINE,
SPLSCN_FEDR,
SPLSCN_ORDERNO,
SPLSCN_ITMCD,
SPLSCN_LOTNO,
SPLSCN_QTY,
SPLSCN_LUPDT,
SPLSCN_USRID,
SPLSCN_MC,
SPLSCN_PROCD,
SPLSCN_UNQCODE

)
VALUES
(
( select [dbo].sato_fun_get_id('PSNRM')),
@SPLSCN_DOC,
@SPLSCN_CAT,
@SPLSCN_LINE,
@SPLSCN_FEDR,
@SPLSCN_ORDERNO,
@SPLSCN_ITMCD,
@SPLSCN_LOTNO,
@SPLSCN_QTY,
GETDATE(),
@SPLSCN_USRID,
@SPLSCN_MCN,
@SPLSCN_PROCD,
@SPLSCN_UNQCODE

)
END


/****** Object:  UserDefinedFunction [dbo].[sato_fun_get_id]    Script Date: 2020-05-06 16:42:55 ******/
SET ANSI_NULLS ON
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_psnno_ost_upload_mega_list]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_psnno_ost_upload_mega_list] @psnno varchar(19) AS
IF MONTH(GETDATE()) != 1
    BEGIN
        SELECT
          PPSN2_PSNNO SPLSCN_DOC
        FROM
          (
            SELECT
              A.*
            FROM
              OPENQUERY(
                [SRVMEGA],
                'SELECT RTRIM(PPSN2_PSNNO) PPSN2_PSNNO
        		,RTRIM(PPSN2_SUBPN) SUBPN
        		,SUM(PPSN2_ACTQT) MGQT
        	FROM PSI_MEGAEMS.dbo.PPSN2_TBL
        	WHERE 
        	SUBSTRING(PPSN2_PSNNO, 8, 4) IN (
        			YEAR(DATEADD(MONTH, - 1, GETDATE()))
        			,YEAR(GETDATE())
        			)
        		AND SUBSTRING(PPSN2_PSNNO, 13, 2) IN (
        			MONTH(DATEADD(MONTH, - 1, GETDATE()))
        			,MONTH(GETDATE())	
        			)		
        	GROUP BY PPSN2_PSNNO
        		,PPSN2_SUBPN
        	'
              ) A
          ) V1
          LEFT JOIN (
            SELECT
              SPLSCN_DOC,
              SPLSCN_ITMCD,
              SUM(SPLSCN_QTY) SCNQT
            FROM
              SPLSCN_TBL
            WHERE
              SUBSTRING(SPLSCN_DOC, 8, 4) IN (
                YEAR(DATEADD(MONTH, - 1, GETDATE())),
                YEAR(GETDATE())
              )
              AND SUBSTRING(SPLSCN_DOC, 13, 2) IN (
                MONTH(DATEADD(MONTH, - 1, GETDATE())),
                MONTH(GETDATE())
              )
              AND SUBSTRING(SPLSCN_DOC, 1, 2) != 'PR'
              AND SPLSCN_SAVED IS NOT NULL
            GROUP BY
              SPLSCN_DOC,
              SPLSCN_ITMCD
          ) V2 ON PPSN2_PSNNO = SPLSCN_DOC
          AND SUBPN = SPLSCN_ITMCD
        WHERE
          MGQT < SCNQT
          AND PPSN2_PSNNO LIKE CONCAT('%', @psnno, '%')
          AND PPSN2_PSNNO NOT IN (
            SELECT
              SPLSCN_DOC
            FROM
              V_SPLSCN_TBLC
            WHERE
              SPLSCN_DATE = CONVERT(DATE, GETDATE())
              and SPLSCN_DOC not like 'PR-%'
            GROUP BY
              SPLSCN_DOC
          )
        GROUP BY
          PPSN2_PSNNO
    END
ELSE
    BEGIN
        SELECT
          PPSN2_PSNNO SPLSCN_DOC
        FROM
          (
            SELECT
              A.*
            FROM
              OPENQUERY(
                [SRVMEGA],
                'SELECT RTRIM(PPSN2_PSNNO) PPSN2_PSNNO
        		,RTRIM(PPSN2_SUBPN) SUBPN
        		,SUM(PPSN2_ACTQT) MGQT
        	FROM PSI_MEGAEMS.dbo.PPSN2_TBL
        	WHERE 
        	SUBSTRING(PPSN2_PSNNO, 8, 4) IN (
        			YEAR(DATEADD(MONTH, - 1, GETDATE()))
        			,YEAR(GETDATE())
        			)
        		AND SUBSTRING(PPSN2_PSNNO, 13, 2) IN (
        			MONTH(DATEADD(MONTH, - 1, GETDATE()))
        			,MONTH(GETDATE())	
        			)		
        	GROUP BY PPSN2_PSNNO
        		,PPSN2_SUBPN
        	'
              ) A
          ) V1
          LEFT JOIN (
            SELECT
              SPLSCN_DOC,
              SPLSCN_ITMCD,
              SUM(SPLSCN_QTY) SCNQT
            FROM
              SPLSCN_TBL
            WHERE
              SUBSTRING(SPLSCN_DOC, 8, 4) IN (
                YEAR(DATEADD(MONTH, - 1, GETDATE())),
                YEAR(GETDATE())
              )
              AND SUBSTRING(SPLSCN_DOC, 13, 2) IN (
                MONTH(DATEADD(MONTH, - 1, GETDATE())),
                MONTH(GETDATE())
              )
              AND SUBSTRING(SPLSCN_DOC, 1, 2) != 'PR'
              AND SPLSCN_SAVED IS NOT NULL
            GROUP BY
              SPLSCN_DOC,
              SPLSCN_ITMCD
          ) V2 ON PPSN2_PSNNO = SPLSCN_DOC
          AND SUBPN = SPLSCN_ITMCD
        WHERE
          MGQT < SCNQT
          AND PPSN2_PSNNO LIKE CONCAT('%', @psnno, '%')
          AND PPSN2_PSNNO NOT IN (
            SELECT
              SPLSCN_DOC
            FROM
              V_SPLSCN_TBLC
            WHERE
              SPLSCN_DATE = CONVERT(DATE, GETDATE())
              and SPLSCN_DOC not like 'PR-%'
            GROUP BY
              SPLSCN_DOC
          )
          AND SUBSTRING(PPSN2_PSNNO, 8, 7) NOT IN (CONCAT(YEAR(DATEADD(MONTH, - 1, GETDATE())),'-01'))
        GROUP BY
          PPSN2_PSNNO
    END
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_psnno_ost_upload_return_mega_list]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_psnno_ost_upload_return_mega_list] @psnno varchar(19) AS
IF MONTH(GETDATE()) != 1
    BEGIN
        SELECT
          PPSN2_PSNNO SPLSCN_DOC
          ,MAX(CONFORM_DATE) CONFORM_DATE
        FROM
          (
            SELECT
              A.*
            FROM
              OPENQUERY(
                [SRVMEGA],
                'SELECT RTRIM(PPSN2_PSNNO) PPSN2_PSNNO
        		,RTRIM(PPSN2_SUBPN) SUBPN
        		,SUM(PPSN2_RTNQT) MGQT
        	FROM PSI_MEGAEMS.dbo.PPSN2_TBL
        	WHERE 
        	SUBSTRING(PPSN2_PSNNO, 8, 4) IN (
        			YEAR(DATEADD(MONTH, - 1, GETDATE()))
        			,YEAR(GETDATE())
        			)
        		AND SUBSTRING(PPSN2_PSNNO, 13, 2) IN (
        			MONTH(DATEADD(MONTH, - 1, GETDATE()))
        			,MONTH(GETDATE())	
        			)		
        	GROUP BY PPSN2_PSNNO
        		,PPSN2_SUBPN
        	'
              ) A
          ) V1
          LEFT JOIN (
            SELECT
              RETSCN_SPLDOC,
              RETSCN_ITMCD,
              SUM(RETSCN_QTYAFT) SCNQT,
              MAX(RETSCN_CNFRMDT) CONFORM_DATE
            FROM
              RETSCN_TBL
            WHERE
              SUBSTRING(RETSCN_SPLDOC, 8, 4) IN (
                YEAR(DATEADD(MONTH, - 1, GETDATE())),
                YEAR(GETDATE())
              )
              AND SUBSTRING(RETSCN_SPLDOC, 13, 2) IN (
                MONTH(DATEADD(MONTH, - 1, GETDATE())),
                MONTH(GETDATE())
              )
              AND SUBSTRING(RETSCN_SPLDOC, 1, 2) != 'PR'
              AND RETSCN_SAVED IS NOT NULL
            GROUP BY
              RETSCN_SPLDOC,
              RETSCN_ITMCD
          ) V2 ON PPSN2_PSNNO = RETSCN_SPLDOC
          AND SUBPN = RETSCN_ITMCD
        WHERE
          MGQT < SCNQT
          AND PPSN2_PSNNO LIKE CONCAT('%', @psnno, '%')
          AND PPSN2_PSNNO NOT IN (
            SELECT
              SPLSCN_DOC
            FROM
              V_SPLSCN_TBLC
            WHERE
              SPLSCN_DATE = CONVERT(DATE, GETDATE())
              and SPLSCN_DOC not like 'PR-%'
            GROUP BY
              SPLSCN_DOC
          )
        GROUP BY
          PPSN2_PSNNO
        ORDER BY 2
    END
ELSE
    BEGIN
        SELECT
          PPSN2_PSNNO SPLSCN_DOC
          ,MAX(CONFORM_DATE) CONFORM_DATE
        FROM
          (
            SELECT
              A.*
            FROM
              OPENQUERY(
                [SRVMEGA],
                'SELECT RTRIM(PPSN2_PSNNO) PPSN2_PSNNO
        		,RTRIM(PPSN2_SUBPN) SUBPN
        		,SUM(PPSN2_RTNQT) MGQT
        	FROM PSI_MEGAEMS.dbo.PPSN2_TBL
        	WHERE 
        	SUBSTRING(PPSN2_PSNNO, 8, 4) IN (
        			YEAR(DATEADD(MONTH, - 1, GETDATE()))
        			,YEAR(GETDATE())
        			)
        		AND SUBSTRING(PPSN2_PSNNO, 13, 2) IN (
        			MONTH(DATEADD(MONTH, - 1, GETDATE()))
        			,MONTH(GETDATE())
        			)	
        	
        	GROUP BY PPSN2_PSNNO
        		,PPSN2_SUBPN
        	'
              ) A
          ) V1
          LEFT JOIN (
            SELECT
              RETSCN_SPLDOC,
              RETSCN_ITMCD,
              SUM(RETSCN_QTYAFT) SCNQT,
              MAX(RETSCN_CNFRMDT) CONFORM_DATE
            FROM
              RETSCN_TBL
            WHERE
              SUBSTRING(RETSCN_SPLDOC, 8, 4) IN (
                YEAR(DATEADD(MONTH, - 1, GETDATE())),
                YEAR(GETDATE())
              )
              AND SUBSTRING(RETSCN_SPLDOC, 13, 2) IN (
                MONTH(DATEADD(MONTH, - 1, GETDATE())),
                MONTH(GETDATE())
              )
              AND SUBSTRING(RETSCN_SPLDOC, 1, 2) != 'PR'
              AND RETSCN_SAVED IS NOT NULL
            GROUP BY
              RETSCN_SPLDOC,
              RETSCN_ITMCD
          ) V2 ON PPSN2_PSNNO = RETSCN_SPLDOC
          AND SUBPN = RETSCN_ITMCD
        WHERE
          MGQT < SCNQT
          AND PPSN2_PSNNO LIKE CONCAT('%', @psnno, '%')
          AND PPSN2_PSNNO NOT IN (
            SELECT
              SPLSCN_DOC
            FROM
              V_SPLSCN_TBLC
            WHERE
              SPLSCN_DATE = CONVERT(DATE, GETDATE())
              and SPLSCN_DOC not like 'PR-%'
            GROUP BY
              SPLSCN_DOC
          )
          AND SUBSTRING(PPSN2_PSNNO, 8, 7) NOT IN (CONCAT(YEAR(DATEADD(MONTH, - 1, GETDATE())),'-01'))
        GROUP BY
          PPSN2_PSNNO
        ORDER BY 2
    END
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_rm_in_fg]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_rm_in_fg] 
@item varchar(45),
@lot varchar(45),
@stock char(1)
as

if @stock='1' 
	begin
	SELECT ITH_SER
		,ITH_ITMCD
		,RTRIM(MITM_ITMD1) MITM_ITMD1
		,STKQTY
		,SERD2_JOB
		,ITH_WH
	FROM (
		SELECT ITH_SER
			,ITH_ITMCD
			,SUM(ITH_QTY) STKQTY
			,ITH_WH
		FROM ITH_TBL
		WHERE ITH_WH IN (
				'AFWH3'
				,'QAFG'
				)
			AND isnull(ITH_SER, '') != ''
		GROUP BY ITH_SER
			,ITH_ITMCD
			,ITH_WH
		HAVING SUM(ITH_QTY) > 0
		) V1
	LEFT JOIN (
		SELECT SERD2_SER
			,SERD2_JOB
		FROM SERD2_TBL
		WHERE SERD2_ITMCD = @item
			AND SERD2_LOTNO LIKE CONCAT (
				'%'
				,@lot
				,'%'
				)
		GROUP BY SERD2_SER
			,SERD2_JOB
		) V2 ON ITH_SER = SERD2_SER
	LEFT JOIN MITM_TBL ON ITH_ITMCD = MITM_ITMCD
	WHERE SERD2_SER IS NOT NULL
	ORDER BY ITH_ITMCD
	END
else
	BEGIN
		SELECT ITH_SER
		,ITH_ITMCD
		,RTRIM(MITM_ITMD1) MITM_ITMD1
		,STKQTY
		,SERD2_JOB
		,ITH_WH
	FROM (
		SELECT ITH_SER
			,ITH_ITMCD
			,SUM(ITH_QTY) STKQTY
			,ITH_WH
		FROM ITH_TBL
		WHERE ITH_WH IN (
				'AFWH3'
				,'QAFG'
				)
			AND isnull(ITH_SER, '') != ''
		GROUP BY ITH_SER
			,ITH_ITMCD
			,ITH_WH		
		) V1
	LEFT JOIN (
		SELECT SERD2_SER
			,SERD2_JOB
		FROM SERD2_TBL
		WHERE SERD2_ITMCD = @item
			AND SERD2_LOTNO LIKE CONCAT (
				'%'
				,@lot
				,'%'
				)
		GROUP BY SERD2_SER
			,SERD2_JOB
		) V2 ON ITH_SER = SERD2_SER
	LEFT JOIN MITM_TBL ON ITH_ITMCD = MITM_ITMCD
	WHERE SERD2_SER IS NOT NULL
	ORDER BY ITH_ITMCD
	END

GO
/****** Object:  StoredProcedure [dbo].[wms_sp_rmstock]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_rmstock]
@BG VARCHAR(15),
@DATESEL DATE
AS

SELECT *, (ARWH+NRWH2+ARWH0PD+PLANT2+QA) STOCK  FROM
	(SELECT RTRIM(ITRN_ITMCD) ITRN_ITMCD,RTRIM(MITM_ITMD1) ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,
		SUM( CASE WHEN ITRN_LOCCD='ARWH2' THEN 
			CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT 
			ELSE -1*ITRN_TRNQT END
		ELSE 
			0 END)  ARWH,
	SUM( CASE WHEN ITRN_LOCCD='NRWH2' THEN 
			CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT 
			ELSE -1*ITRN_TRNQT END
		ELSE 
			0 END)  NRWH2,
	SUM( CASE WHEN ITRN_LOCCD='ARWH0PD' THEN 
			CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT 
			ELSE -1*ITRN_TRNQT END
		ELSE 
			0 END)  ARWH0PD,
	SUM( CASE WHEN ITRN_LOCCD='PLANT2' THEN
			CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT 
			ELSE -1*ITRN_TRNQT END
		ELSE 
		0 END) PLANT2,
	SUM( CASE WHEN ITRN_LOCCD='QA' THEN
			CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT 
			ELSE -1*ITRN_TRNQT END
		ELSE 
		0 END) QA,
		'' JOB,
		'' PSN,
		0 JOBUNIT,
		0 QTYPCS,
		0 LOGRTN		
			FROM XITRN_TBL
			LEFT JOIN XMITM_V ON ITRN_ITMCD=MITM_ITMCD
			WHERE ITRN_ISUDT<=@DATESEL AND ITRN_LOCCD in ('PLANT2','ARWH2','QA','ARWH0PD','NRWH2')
			AND ITRN_BSGRP=@BG
	GROUP BY ITRN_ITMCD,MITM_ITMD1,MITM_SPTNO) VMEGA		
	ORDER BY 1
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_rmstock_plant1]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_rmstock_plant1]
@BG VARCHAR(15),
@DATESEL DATE
AS

SELECT *, (ARWH+NRWH2+ARWH0PD+PLANT2+QA) STOCK  FROM
	(SELECT RTRIM(ITRN_ITMCD) ITRN_ITMCD,RTRIM(MITM_ITMD1) ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,
		SUM( CASE WHEN ITRN_LOCCD='ARWH1' THEN 
			CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT 
			ELSE -1*ITRN_TRNQT END
		ELSE 
			0 END)  ARWH,
	SUM( CASE WHEN ITRN_LOCCD='NRWH2' THEN 
			CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT 
			ELSE -1*ITRN_TRNQT END
		ELSE 
			0 END)  NRWH2,
	SUM( CASE WHEN ITRN_LOCCD='ARWH0PD' THEN 
			CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT 
			ELSE -1*ITRN_TRNQT END
		ELSE 
			0 END)  ARWH0PD,
	SUM( CASE WHEN ITRN_LOCCD='PLANT1' THEN
			CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT 
			ELSE -1*ITRN_TRNQT END
		ELSE 
		0 END) PLANT2,
	SUM( CASE WHEN ITRN_LOCCD='QA' THEN
			CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT 
			ELSE -1*ITRN_TRNQT END
		ELSE 
		0 END) QA,
		'' JOB,
		'' PSN,
		0 JOBUNIT,
		0 QTYPCS,
		0 LOGRTN		
			FROM XITRN_TBL
			LEFT JOIN XMITM_V ON ITRN_ITMCD=MITM_ITMCD
			WHERE ITRN_ISUDT<=@DATESEL AND ITRN_LOCCD in ('PLANT1','ARWH1','QA','ARWH0PD')
			AND ITRN_BSGRP=@BG
	GROUP BY ITRN_ITMCD,MITM_ITMD1,MITM_SPTNO) VMEGA		
	ORDER BY 1
GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_SaveToITH]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[WMS_sp_SaveToITH]
(
@ITH_ITMCD [varchar](50),
@ITH_DATE [DATETIME],
@ITH_FORM [varchar](50),
@ITH_DOC [varchar](50),
@ITH_QTY [decimal](12, 3),
@ITH_WH [varchar](50),
@ITH_SER [varchar](50),
@ITH_REMARK [varchar](50),
@ITH_LOC [varchar](50),
@ITH_LUPDT [varchar](50),
@ITH_USRID [varchar](50),
@status   [varchar](50)
)

		AS
BEGIN 
     SET NOCOUNT ON 
--SATO HERE
--IF (@status ='Insert')
--BEGIN
--INSERT INTO [ITH_TBL] 
--(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LINE,ITH_LOC,ITH_LUPDT,ITH_USRID)
--VALUES(@ITH_ITMCD,CAST(getdate() AS date),@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK,
--([dbo].[fun_ithline] ()),@ITH_LOC,@ITH_LUPDT,@ITH_USRID)
--END

--ELSE IF(@status='Update')
--BEGIN
--UPDATE ITH_TBL SET ITH_QTY=ITH_QTY+@ITH_QTY,ITH_LUPDT=@ITH_LUPDT WHERE ITH_ITMCD=@ITH_ITMCD AND ITH_DATE=CAST(getdate() as date)
--AND ITH_DOC=@ITH_DOC AND ITH_FORM=@ITH_FORM
--END
--SATO END HERE
DECLARE @BG VARCHAR(50)
DECLARE @BOOKID VARCHAR(50)
IF (@status ='Insert')
	BEGIN
	if (@ITH_FORM='OUT-WH-FG')
		BEGIN
		INSERT INTO [ITH_TBL] 
		(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LOC,ITH_LUPDT,ITH_USRID)
		VALUES(@ITH_ITMCD,CAST(getdate() AS date),@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK,dbo.fun_delv_location_be4(@ITH_WH,@ITH_SER),@ITH_LUPDT,@ITH_USRID)
		END
	else if (@ITH_FORM='INC-DO')
		BEGIN
		DECLARE @TGLBC date
		set @TGLBC = (SELECT max(RCV_BCDATE) RCV_BCDATE FROM RCV_TBL WHERE RCV_DONO=@ITH_DOC)
		--2021-12-10
		--x===========PERUBAHAN ATURAN========
		--Tidak perlu menyimpan ket ITH_TBL karena INC-DO akan dilakukan ketika EXIM melakukan update
		--===================================
		--INSERT INTO [ITH_TBL] 
		--(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LINE,ITH_LOC,ITH_LUPDT,ITH_USRID)
		--VALUES(@ITH_ITMCD,@TGLBC,@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK,
		--([dbo].[fun_ithline] ()),dbo.fun_delv_location_be4(@ITH_WH,@ITH_SER),CONCAT(@TGLBC,' ','07:01:01'),@ITH_USRID)
		END
	else if (@ITH_FORM='OUT-WH-RM')
		BEGIN
	
			SET @BG = (SELECT TOP 1 RTRIM(SPL_BG) FROM SPL_TBL WHERE SPL_DOC=SUBSTRING(@ITH_DOC,1,19))
			SET @ITH_WH = CASE 
				WHEN @BG='PSI1PPZIEP' THEN 'ARWH1'
				WHEN @BG='PSI2PPZADI' THEN 'ARWH2'
				WHEN @BG='PSI2PPZSTY' THEN 'ARWH2'
				WHEN @BG='PSI2PPZOMI' THEN 'ARWH2'
				WHEN @BG='PSI2PPZTDI' THEN 'ARWH2'
				WHEN @BG='PSI2PPZINS' THEN 'NRWH2'
				WHEN @BG='PSI2PPZOMC' THEN 'NRWH2'			
				WHEN @BG='PSI2PPZSSI' THEN 'NRWH2'			
				END		
		INSERT INTO [ITH_TBL] 
		(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LOC,ITH_LUPDT,ITH_USRID)
		VALUES(@ITH_ITMCD,@ITH_LUPDT,@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK
		,@ITH_LOC,@ITH_LUPDT,@ITH_USRID)
		END
	else if (@ITH_FORM='INC-PRD-RM')
		BEGIN	
			SET @BG = (SELECT TOP 1 RTRIM(SPL_BG) FROM SPL_TBL WHERE SPL_DOC=SUBSTRING(@ITH_DOC,1,19))		
			SET @ITH_WH = CASE 
				WHEN @BG='PSI1PPZIEP' THEN 'PLANT1'
				WHEN @BG='PSI2PPZADI' THEN 'PLANT2'
				WHEN @BG='PSI2PPZSTY' THEN 'PLANT2'
				WHEN @BG='PSI2PPZOMI' THEN 'PLANT2'
				WHEN @BG='PSI2PPZTDI' THEN 'PLANT2'
				WHEN @BG='PSI2PPZINS' THEN 'PLANT_NA'
				WHEN @BG='PSI2PPZOMC' THEN 'PLANT_NA'			
				WHEN @BG='PSI2PPZSSI' THEN 'PLANT_NA'			
				END
		INSERT INTO [ITH_TBL] 
		(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LOC,ITH_LUPDT,ITH_USRID)
		VALUES(@ITH_ITMCD,@ITH_LUPDT,@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK,
		@ITH_LOC,@ITH_LUPDT,@ITH_USRID)
		END
	ELSE
		BEGIN
		INSERT INTO [ITH_TBL] 
		(ITH_ITMCD,ITH_DATE,ITH_FORM,ITH_DOC,ITH_QTY,ITH_WH,ITH_SER,ITH_REMARK,ITH_LINE,ITH_LOC,ITH_LUPDT,ITH_USRID)
		VALUES(@ITH_ITMCD,CAST(getdate() AS date),@ITH_FORM,@ITH_DOC,@ITH_QTY,@ITH_WH,@ITH_SER,@ITH_REMARK,
		([dbo].[fun_ithline] ()),@ITH_LOC,GETDATE(),@ITH_USRID)
		END
	END


ELSE IF(@status='Update')
BEGIN
UPDATE ITH_TBL SET ITH_QTY=ITH_QTY+@ITH_QTY WHERE ITH_ITMCD=@ITH_ITMCD AND ITH_DATE=CAST(getdate() as date)
AND ITH_DOC=@ITH_DOC AND ITH_FORM=@ITH_FORM
END


END
GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_SaveToTLWS]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


Create PROCEDURE [dbo].[WMS_sp_SaveToTLWS]
(
@spid [varchar](50)
)

		AS
BEGIN 
     SET NOCOUNT ON 

		BEGIN
		INSERT INTO [WMS_TLWS_LOG] 
		(TLWS_BOMRV,TLWS_JOBNO,TLWS_LINENO,TLWS_LUPBY,TLWS_LUPDT,TLWS_MDLCD,TLWS_PROCD,TLWS_PSNNO,TLWS_SPID)
		select TLWS_BOMRV,TLWS_JOBNO,TLWS_LINENO,TLWS_LUPBY,TLWS_LUPDT,TLWS_MDLCD,TLWS_PROCD,TLWS_PSNNO,TLWS_SPID from WMS_TLWS_TBL where TLWS_SPID = @spid
		END
	


END
GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_SaveToTWMP]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[WMS_sp_SaveToTWMP]
(
@TWMP_WO [varchar](100),
@TWMP_PRO [VARCHAR](100),
@TWMP_LINE [varchar](50),
@TWMP_MDL [varchar](100),
@TWMP_BOM [VARCHAR](50),
@TWMP_MC [varchar](100),
@TWMP_MCZ [varchar](50),
@TWMP_ITMCD [varchar](50),
@TWMP_CURRLOT [varchar](50),
@TWMP_NLOT [varchar](50),
@TWMP_PIC   [varchar](50),
@TWMP_DT	[DATETIME],
@TWMP_ID	[VARCHAR](100)
)

		AS
BEGIN 
     SET NOCOUNT ON 

BEGIN
insert into WMS_TWMP_TBL(TWMP_WONO,TWMP_PROCD,TWMP_LINENO,TWMP_MCMCZITM,TWMP_LOCCD,twmp_mc,TWMP_MCZ,TWMP_ITMCD,TWMP_CURRLOTNO,TWMP_NITMCD,TWMP_NLOTNO,TWMP_LUPDT,TWMP_LUPBY,TWMP_LSWSN)
select @TWMP_WO as [WO],MBLA_PROCD,MBLA_LINENO,(mbla_mc+' '+MBLA_MCZ+' '+MBLA_ITMCD) as [mcmczitm],MBLA_LOCCD,MBLA_MC,MBLA_MCZ,MBLA_ITMCD,@TWMP_CURRLOT as [lot],MBLA_ITMCD,@TWMP_NLOT as [lot2],@TWMP_DT AS [MBLA_LUPDT],@TWMP_PIC AS [MBLA_LUPBY],@TWMP_ID AS [SPID] from XSTXMBLA_TBL where MBLA_MDLCD = @TWMP_MDL and MBLA_BOMRV = @TWMP_BOM and MBLA_LINENO = @TWMP_LINE and MBLA_PROCD = @TWMP_PRO and mbla_mc = @TWMP_MC and MBLA_MCZ = @TWMP_MCZ and MBLA_ITMCD = @TWMP_ITMCD
END

END
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_select_approver_by_nik]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_select_approver_by_nik]
@nik varchar(9)
as
select TRFSET_APPROVER from TRFH_TBL left join TRFD_TBL on TRFH_DOC=TRFD_DOC
left join TRFSET_TBL on TRFH_LOC_TO=TRFSET_WH
where TRFD_CREATED_BY is not null and TRFD_DELETED_BY is null
AND TRFSET_DELETED_BY IS NULL AND TRFSET_APPROVER=@nik
group by TRFSET_APPROVER
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_select_booked_exbc_by_txid]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_select_booked_exbc_by_txid]
@TXID VARCHAR(25)
AS
SELECT VBOOK.*
	,RCV_KPPBC
	,RCV_PRPRC
	,RCV_HSCD
	,RCV_PPN
	,RCV_PPH
	,RCV_BM
	,RCV_ZNOURUT
	,RTRIM(MITM_ITMD1) MITM_ITMD1
	,RTRIM(MITM_SPTNO) MITM_SPTNO
	,RTRIM(MITM_STKUOM) MITM_STKUOM
	,CURRENCY
FROM (
	SELECT UPPER(RTRIM(RPSTOCK_ITMNUM)) BC_ITEM
		,ABS(SUM(RPSTOCK_QTY)) BC_QTY
		,RPSTOCK_BCTYPE BC_TYPE
		,RPSTOCK_BCNUM BC_NUM
		,RPSTOCK_BCDATE BC_DATE
		,RPSTOCK_NOAJU BC_AJU
	FROM ZRPSAL_BCSTOCK
	WHERE RPSTOCK_REMARK = @TXID
	GROUP BY RPSTOCK_ITMNUM
		,RPSTOCK_BCTYPE
		,RPSTOCK_BCNUM
		,RPSTOCK_BCDATE
		,RPSTOCK_NOAJU
	) VBOOK
LEFT JOIN (
	SELECT RCV_RPNO
		,RCV_BCNO
		,RCV_ITMCD
		,MAX(RCV_KPPBC) RCV_KPPBC
		,MAX(RCV_PRPRC) RCV_PRPRC
		,MAX(RCV_HSCD) RCV_HSCD
		,MAX(RCV_PPN) RCV_PPN
		,MAX(RCV_PPH) RCV_PPH
		,MAX(RCV_BM) RCV_BM
		,MAX(RCV_ZNOURUT) RCV_ZNOURUT
		,MAX(RTRIM(MSUP_SUPCR)) CURRENCY
	FROM RCV_TBL
	LEFT JOIN MSUP_TBL ON RCV_SUPCD=MSUP_SUPCD
	GROUP BY RCV_RPNO
		,RCV_BCNO
		,RCV_ITMCD
	) VRCV ON VBOOK.BC_AJU = RCV_RPNO
	AND VBOOK.BC_NUM = VRCV.RCV_BCNO
	AND BC_ITEM = RCV_ITMCD
LEFT JOIN MITM_TBL ON BC_ITEM=MITM_ITMCD
ORDER BY BC_DATE
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_select_invoice_posting]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_select_invoice_posting] @txid VARCHAR(25)
AS
SELECT VINV.*
	,MDEL_ZNAMA
	,MDEL_ADDRCUSTOMS
	,DLV_DATE
	,DLV_INVNO
	,DLV_SMTINVNO
	,MCUS_CURCD
	,DLV_INVDT
	,DLV_NOPEN
	,DLV_ITMD1
	,DLV_ITMSPTNO
FROM (
	SELECT DLVRMDOC_TXID
		,upper(DLVRMDOC_ITMID) DLVRMDOC_ITMID
		,RTRIM(MITM_ITMD1) MITM_ITMD1
		,DLVRMDOC_PRPRC
		,sum(DLVRMDOC_ITMQT) ITMQT
		,DLVRMDOC_PRPRC * SUM(DLVRMDOC_ITMQT) AMNT
		,RTRIM(MITM_STKUOM) MITM_STKUOM
		,DLVRMDOC_DO
	FROM DLVRMDOC_TBL
	LEFT JOIN MITM_TBL ON DLVRMDOC_ITMID = MITM_ITMCD
	WHERE DLVRMDOC_TXID = @txid
	GROUP BY DLVRMDOC_ITMID
		,MITM_ITMD1
		,DLVRMDOC_PRPRC
		,DLVRMDOC_TXID
		,MITM_STKUOM
		,DLVRMDOC_DO
	) VINV
LEFT JOIN (
	SELECT DLV_ID
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_DATE
		,ISNULL(DLV_INVNO, '') DLV_INVNO
		,ISNULL(DLV_SMTINVNO, '') DLV_SMTINVNO
		,RTRIM(MCUS_CURCD) MCUS_CURCD
		,DLV_INVDT
		,isnull(DLV_NOPEN, '') DLV_NOPEN
		,ISNULL(MITMGRP_ITMCD, DLV_ITMCD) DLV_ITMCD
		,DLV_ITMD1
		,DLV_ITMSPTNO
	FROM DLV_TBL
	LEFT JOIN MDEL_TBL ON DLV_CONSIGN = MDEL_DELCD
	LEFT JOIN MCUS_TBL ON DLV_CUSTCD = MCUS_CUSCD
	LEFT JOIN MITMGRP_TBL ON DLV_ITMCD = MITMGRP_ITMCD_GRD
	WHERE DLV_ID = @txid
	GROUP BY DLV_ID
		,MDEL_ZNAMA
		,MDEL_ADDRCUSTOMS
		,DLV_DATE
		,DLV_INVNO
		,DLV_SMTINVNO
		,MCUS_CURCD
		,DLV_INVDT
		,DLV_NOPEN
		,DLV_ITMCD
		,DLV_ITMD1
		,DLV_ITMSPTNO
		,MITMGRP_ITMCD
	) VH ON DLVRMDOC_TXID = DLV_ID
	AND DLVRMDOC_ITMID = DLV_ITMCD
ORDER BY DLVRMDOC_ITMID
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_select_WH_by_serah_terima_RC]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_select_WH_by_serah_terima_RC]
@doc varchar(50)
as
select RCV_WH from SERRC_TBL 
left join SER_TBL on SERRC_SER=SER_ID
left join (select RCV_INVNO,RCV_WH from RCV_TBL where RCV_INVNO = @doc group by RCV_INVNO,RCV_WH) VRCV ON SER_DOC=RCV_INVNO
where SERRC_DOCST = @doc
GROUP BY RCV_WH
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_std_stock_wbg]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_std_stock_wbg] @wh VARCHAR(25)
	,@itemcode VARCHAR(50)
	,@pdate DATE
AS
BEGIN
	SET NOCOUNT ON;

	DECLARE @outwh VARCHAR(20)
	DECLARE @closingwh VARCHAR(20)

	SET @outwh = CASE 
			WHEN @wh = 'AFWH3'
				OR @wh = 'AFSMT'
				THEN 'ARSHP'
			ELSE @wh
			END
	SET @closingwh = CASE 
			WHEN @wh = 'AFSMT'
				THEN 'AFWH3'
			ELSE @wh
			END

	SELECT ITH_WH
		,VSTOCK.ITH_ITMCD
		,RTRIM(MITM_ITMD1) MITM_ITMD1
		,RTRIM(MITM_SPTNO) MITM_SPTNO
		,BEFQTY
		,INQTY
		,PRPQTY
		,abs(OUTQTY) OUTQTY
		,CASE 
			WHEN @wh = 'AFSMT'
				THEN (STOCKQTY + ISNULL(PRPQTY, 0))
			ELSE STOCKQTY
			END STOCKQTY
		,MITM_STKUOM
		,UPPER(ISNULL(MITM_NCAT, '')) MITM_NCAT
		,LUPDT
		,MITM_ACTIVE
		,LOC
	FROM (
		SELECT ITH_WH
			,ITH_ITMCD
			,MITM_ITMD1
			,MITM_SPTNO
			,SUM(ITH_QTY) STOCKQTY
			,MITM_STKUOM
			,MITM_NCAT
			,MAX(ITH_LUPDT) LUPDT
			,SUM(CASE WHEN ITH_DATEC <@pdate THEN ITH_QTY END ) BEFQTY
			,ISNULL(MITM_ACTIVE,'') MITM_ACTIVE
			,MAX(LOC) LOC
		FROM v_ith_tblc a
		LEFT JOIN MITM_TBL b ON a.ITH_ITMCD = b.MITM_ITMCD
		LEFT JOIN (SELECT  A.ITMLOC_ITM,MAX(A.ITMLOC_LOC) LOC FROM ITMLOC_TBL A GROUP BY A.ITMLOC_ITM) VLOC ON ITMLOC_ITM=MITM_ITMCD
		WHERE ITH_WH = @closingwh
			AND ITH_ITMCD LIKE CONCAT (
				'%'
				,@itemcode
				,'%'
				)
			AND ITH_FORM NOT IN (
				'SASTART'
				,'SA'
				)
			AND ITH_DATEC <= @pdate
		GROUP BY ITH_ITMCD
			,ITH_WH
			,MITM_SPTNO
			,MITM_STKUOM
			,MITM_ITMD1
			,MITM_NCAT
			,MITM_ACTIVE
		) VSTOCK	
	LEFT JOIN (
		SELECT ITH_ITMCD
			,SUM(ITH_QTY) INQTY
		FROM v_ith_tblc a
		WHERE ITH_WH = @closingwh
			AND ITH_ITMCD LIKE CONCAT (
				'%'
				,@itemcode
				,'%'
				)
			AND ITH_FORM NOT IN (
				'SPLIT-FG-LBL'
				,'JOIN_IN'
				,'JOIN_OUT'
				,'CANCEL-SHIP'
				,'TRFIN-FG'
				)
			AND ITH_FORM NOT IN (
				'SASTART'
				,'SA'
				)
			AND ITH_DATEC = @pdate
			AND ITH_QTY > 0
		GROUP BY ITH_ITMCD
		) VIN ON VSTOCK.ITH_ITMCD = VIN.ITH_ITMCD
	LEFT JOIN (
		SELECT ITH_ITMCD
			,SUM(ITH_QTY) OUTQTY
		FROM v_ith_tblc a
		WHERE ITH_WH = @outwh
			AND ITH_ITMCD LIKE CONCAT (
				'%'
				,@itemcode
				,'%'
				)
			AND ITH_FORM NOT IN (
				'SPLIT-FG-LBL'
				,'JOIN_IN'
				,'JOIN_OUT'
				,'CANCEL-SHIP'
				)
			AND ITH_FORM NOT IN (
				'SASTART'
				,'SA'
				)
			AND ITH_DATEC = @pdate
			AND ITH_QTY < 0
		GROUP BY ITH_ITMCD
		) VOUT ON VSTOCK.ITH_ITMCD = VOUT.ITH_ITMCD
	LEFT JOIN (
		SELECT ITH_ITMCD
			,SUM(ITH_QTY) PRPQTY
		FROM v_ith_tblc a
		WHERE ITH_WH = 'ARSHP'
			AND ITH_ITMCD LIKE CONCAT (
				'%'
				,@itemcode
				,'%'
				)
			AND ITH_FORM NOT IN (
				'SASTART'
				,'SA'
				)
			AND ITH_DATEC <= @pdate
		GROUP BY ITH_ITMCD
		) VPREP ON VSTOCK.ITH_ITMCD = VPREP.ITH_ITMCD
	ORDER BY VSTOCK.ITH_ITMCD ASC
END
GO
/****** Object:  StoredProcedure [dbo].[wms_sp_unconfirmed_psn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_sp_unconfirmed_psn]
@psn_year_month varchar(8)
as
SELECT VPSN.*,ISUDT FROM
(select RTRIM(PPSN2_PSNNO) PPSN2_PSNNO from XPPSN2 where PPSN2_PSNNO like concat('%',@psn_year_month,'%')
AND PPSN2_RTNQT>0
GROUP BY PPSN2_PSNNO)
VPSN
LEFT JOIN
	(
		SELECT SUBSTRING(ITH_DOC,1,19) PSN FROM v_ith_tblc WHERE ITH_FORM LIKE '%RET%'
		AND ITH_DOC LIKE concat('%',@psn_year_month,'%')
		AND ITH_WH IN ('ARWH1','ARWH2')
		GROUP BY SUBSTRING(ITH_DOC,1,19)
	) V ON PPSN2_PSNNO=PSN
LEFT JOIN (
	SELECT SUBSTRING(ITRN_DOCNO,1,19) ITRN_DOCNO,CONVERT(DATE,ITRN_ISUDT) ISUDT FROM XITRN_TBL WHERE 
	ITRN_DOCCD NOT IN ('WOR','GRN') AND ITRN_PRGID='ME9PRTN9'
	AND ITRN_DOCNO like concat('%',@psn_year_month,'%')
	AND ITRN_DOCNO LIKE '%-R%'
	GROUP BY SUBSTRING(ITRN_DOCNO,1,19),ITRN_ISUDT
) V2 ON PPSN2_PSNNO=ITRN_DOCNO
WHERE PSN IS NULL
GO
/****** Object:  StoredProcedure [dbo].[WMS_sp_Updt_fdr]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO





Create PROCEDURE [dbo].[WMS_sp_Updt_fdr]
(
@Fdr   [varchar](50) ,
@mc [varchar](50) ,
@mcz [varchar](50),
@spid [varchar](50),
@flg  [numeric]
)


AS
BEGIN
	SET NOCOUNT ON;

	BEGIN
Update wms_mst_fdr set fdr_mc = @mc,fdr_mcz = @mcz,fdr_spid = @spid,fdr_flg = @flg where fdr_no = @Fdr
END


END




GO
/****** Object:  StoredProcedure [dbo].[wms_spSelectStockWBGByDescription]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[wms_spSelectStockWBGByDescription] @wh VARCHAR(25)
	,@itemDescription VARCHAR(50)
	,@pdate DATE
AS
BEGIN
	SET NOCOUNT ON;

	DECLARE @outwh VARCHAR(20)
	DECLARE @closingwh VARCHAR(20)

	SET @outwh = CASE 
			WHEN @wh = 'AFWH3'
				OR @wh = 'AFSMT'
				THEN 'ARSHP'
			ELSE @wh
			END
	SET @closingwh = CASE 
			WHEN @wh = 'AFSMT'
				THEN 'AFWH3'
			ELSE @wh
			END

	SELECT ITH_WH
		,VSTOCK.ITH_ITMCD
		,RTRIM(MITM_ITMD1) MITM_ITMD1
		,RTRIM(MITM_SPTNO) MITM_SPTNO
		,BEFQTY
		,INQTY
		,PRPQTY
		,abs(OUTQTY) OUTQTY
		,CASE 
			WHEN @wh = 'AFSMT'
				THEN (STOCKQTY + ISNULL(PRPQTY, 0))
			ELSE STOCKQTY
			END STOCKQTY
		,MITM_STKUOM
		,UPPER(ISNULL(MITM_NCAT, '')) MITM_NCAT
		,LUPDT
		,MITM_ACTIVE
	FROM (
		SELECT ITH_WH
			,ITH_ITMCD
			,MITM_ITMD1
			,MITM_SPTNO
			,SUM(ITH_QTY) STOCKQTY
			,MITM_STKUOM
			,MITM_NCAT
			,MAX(ITH_LUPDT) LUPDT
			,SUM(CASE WHEN ITH_DATEC <@pdate THEN ITH_QTY END ) BEFQTY
			,ISNULL(MITM_ACTIVE, '') MITM_ACTIVE
		FROM v_ith_tblc a
		LEFT JOIN MITM_TBL b ON a.ITH_ITMCD = b.MITM_ITMCD
		WHERE ITH_WH = @closingwh
			AND MITM_ITMD1 LIKE CONCAT (
				'%'
				,@itemDescription
				,'%'
				)
			AND ITH_FORM NOT IN (
				'SASTART'
				,'SA'
				)
			AND ITH_DATEC <= @pdate
		GROUP BY ITH_ITMCD
			,ITH_WH
			,MITM_SPTNO
			,MITM_STKUOM
			,MITM_ITMD1
			,MITM_NCAT
			,MITM_ACTIVE
		) VSTOCK	
	LEFT JOIN (
		SELECT ITH_ITMCD
			,SUM(ITH_QTY) INQTY
		FROM v_ith_tblc a
		WHERE ITH_WH = @closingwh			
			AND ITH_FORM NOT IN (
				'SPLIT-FG-LBL'
				,'JOIN_IN'
				,'JOIN_OUT'
				,'CANCEL-SHIP'
				,'TRFIN-FG'
				)
			AND ITH_FORM NOT IN (
				'SASTART'
				,'SA'
				)
			AND ITH_DATEC = @pdate
			AND ITH_QTY > 0
		GROUP BY ITH_ITMCD
		) VIN ON VSTOCK.ITH_ITMCD = VIN.ITH_ITMCD
	LEFT JOIN (
		SELECT ITH_ITMCD
			,SUM(ITH_QTY) OUTQTY
		FROM v_ith_tblc a
		WHERE ITH_WH = @outwh			
			AND ITH_FORM NOT IN (
				'SPLIT-FG-LBL'
				,'JOIN_IN'
				,'JOIN_OUT'
				,'CANCEL-SHIP'
				)
			AND ITH_FORM NOT IN (
				'SASTART'
				,'SA'
				)
			AND ITH_DATEC = @pdate
			AND ITH_QTY < 0
		GROUP BY ITH_ITMCD
		) VOUT ON VSTOCK.ITH_ITMCD = VOUT.ITH_ITMCD
	LEFT JOIN (
		SELECT ITH_ITMCD
			,SUM(ITH_QTY) PRPQTY
		FROM v_ith_tblc a
		WHERE ITH_WH = 'ARSHP'			
			AND ITH_FORM NOT IN (
				'SASTART'
				,'SA'
				)
			AND ITH_DATEC <= @pdate
		GROUP BY ITH_ITMCD
		) VPREP ON VSTOCK.ITH_ITMCD = VPREP.ITH_ITMCD
	ORDER BY VSTOCK.ITH_ITMCD ASC
END
GO
/****** Object:  StoredProcedure [dbo].[xsp_megapsnhead]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE procedure [dbo].[xsp_megapsnhead]
@psnno varchar(50),
@lineno varchar(50),
@fr varchar(10)
as
SELECT DISTINCT PPSN1_LINENO, PPSN1_PROCD,convert(date,PPSN1_ISUDT) PPSN1_ISUDT,PPSN1_WONO ,PPSN1_MDLCD,MITM_ITMD1,PPSN1_SIMQT
FROM XPPSN1 a INNER JOIN XMITM_V b on a.PPSN1_MDLCD=b.MITM_ITMCD
WHERE PPSN1_PSNNO=@psnno AND PPSN1_LINENO=@lineno AND PPSN1_FR=@fr
GO
/****** Object:  StoredProcedure [dbo].[xsp_megapsnhead_bypsn]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

create procedure [dbo].[xsp_megapsnhead_bypsn]
@psnno varchar(50)
as
SELECT DISTINCT PPSN1_LINENO, PPSN1_PROCD,convert(date,PPSN1_ISUDT) PPSN1_ISUDT,PPSN1_WONO ,PPSN1_MDLCD,MITM_ITMD1,PPSN1_SIMQT
FROM XPPSN1 a INNER JOIN XMITM_V b on a.PPSN1_MDLCD=b.MITM_ITMCD
WHERE PPSN1_PSNNO=@psnno 
GO
/****** Object:  StoredProcedure [dbo].[xsp_megapsnhead_nofr]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE procedure [dbo].[xsp_megapsnhead_nofr]
    @psnno varchar(50),
    @lineno varchar(50)
as
SELECT DISTINCT RTRIM(PPSN1_LINENO) PPSN1_LINENO, RTRIM(PPSN1_PROCD) PPSN1_PROCD
, convert(date,PPSN1_ISUDT) PPSN1_ISUDT, RTRIM(PPSN1_WONO) PPSN1_WONO 
, RTRIM(PPSN1_MDLCD) PPSN1_MDLCD, RTRIM(MITM_ITMD1) MITM_ITMD1, PPSN1_SIMQT
FROM SRVMEGA.PSI_MEGAEMS.dbo.PPSN1_TBL a INNER JOIN SRVMEGA.PSI_MEGAEMS.dbo.MITM_TBL b on a.PPSN1_MDLCD=b.MITM_ITMCD
WHERE PPSN1_PSNNO=@psnno AND PPSN1_LINENO=@lineno 
GO
/****** Object:  StoredProcedure [dbo].[xsp_megatowms_pis]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE procedure [dbo].[xsp_megatowms_pis] 
@docno varchar(50),
@lineno varchar(25),
@mcz varchar(40)

as
SELECT PIS3_TBL.*,PIS2_QTPER,TWOR_MDLCD,TWOR_LOTSZ,PDPP_CUSCD,TWOR_LOTSZ*PIS2_QTPER MYQTREQ FROM SRVMEGA.PSI_MEGAEMS.dbo.PIS3_TBL 
INNER JOIN SRVMEGA.PSI_MEGAEMS.dbo.PIS2_TBL ON PIS3_DOCNO=PIS2_DOCNO AND  PIS3_MCZ=PIS2_MCZ AND PIS3_LINENO=PIS2_LINENO
AND PIS3_MCZ=PIS2_MCZ AND PIS3_MPART=PIS2_MPART AND PIS3_WONO=PIS2_WONO AND PIS3_FR=PIS2_FR
INNER JOIN SRVMEGA.PSI_MEGAEMS.dbo.TWOR_CIM ON PIS3_WONO=TWOR_WONO
LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.PDPP_TBL f on TWOR_WONO=PDPP_WONO
WHERE PIS3_DOCNO=@docno AND PIS3_MCZ=@mcz AND PIS3_LINENO=@lineno
GO
/****** Object:  StoredProcedure [dbo].[xsp_megatowms_spl]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[xsp_megatowms_spl]
@spmat varchar(50),
@lineno varchar(50),
@cat varchar(50),
@fr char(1)
as 


select RTRIM(PPSN2_PSNNO) PPSN2_PSNNO,RTRIM(PPSN2_DOCNO) PPSN2_DOCNO,RTRIM(PPSN2_ITMCAT) PPSN2_ITMCAT,
CASE WHEN RTRIM(PPSN2_LINENO)='' THEN '_' ELSE RTRIM(PPSN2_LINENO) END PPSN2_LINENO,
CASE WHEN RTRIM(PPSN2_FR)='' THEN '_' ELSE RTRIM(PPSN2_FR) END PPSN2_FR,
CASE WHEN RTRIM(PPSN2_MC)='' THEN '_' ELSE RTRIM(PPSN2_MC) END PPSN2_MC,
CASE WHEN RTRIM(PPSN2_MCZ)='' THEN '_' ELSE RTRIM(PPSN2_MCZ) END PPSN2_MCZ,
ITMLOC_LOC, RTRIM(PPSN2_SUBPN) PPSN2_SUBPN,PPSN2_QTPER,
RTRIM(PPSN2_MSFLG) PPSN2_MSFLG, 
PPSN2_REQQT ,
RTRIM(PPSN2_PROCD) PPSN2_PROCD, 
RTRIM(PPSN2_DATANO) PPSN2_DATANO,
RTRIM(PPSN2_BSGRP) PPSN2_BSGRP,
RTRIM(MITM_RAKNO) MITM_RAKNO
--
from  XPPSN2 a 
LEFT JOIN  ITMLOC_TBL d on a.PPSN2_SUBPN=d.ITMLOC_ITM and PPSN2_BSGRP=ITMLOC_BG
LEFT join MSTLOC_TBL e on MSTLOC_CD=ITMLOC_LOC
LEFT JOIN XMITM_VCIMS ON PPSN2_SUBPN=XMITM_VCIMS.MITM_ITMCD
WHERE PPSN2_PSNNO=@spmat and PPSN2_DOCNO is not null
ORDER BY ITMLOC_LOC DESC
GO
/****** Object:  StoredProcedure [dbo].[xsp_progress_scndo]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[xsp_progress_scndo]
@dono varchar(50)
as 

SELECT RCV_DONO, (SUM(TTLSCN)/SUM(RCV_QTY) *100) PROGRESS FROM
(select RCV_DONO, RCV_ITMCD, RCV_QTY,COALESCE(SUM(RCVSCN_QTY),0) TTLSCN from 
	(select RCV_DONO,RCV_ITMCD,SUM(RCV_QTY) RCV_QTY,RCV_WH FROM RCV_TBL
		GROUP BY RCV_DONO,RCV_ITMCD,RCV_WH) a 
	LEFT JOIN RCVSCN_TBL b ON a.RCV_DONO=b.RCVSCN_DONO and a.RCV_ITMCD=b.RCVSCN_ITMCD
WHERE RCV_DONO=@dono
GROUP BY RCV_DONO,RCV_ITMCD, RCV_QTY) v1
GROUP BY RCV_DONO

GO
/****** Object:  StoredProcedure [dbo].[xsp_progress_scndosave]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[xsp_progress_scndosave]
@dono varchar(50)
as 
SELECT RCV_DONO, (SUM(TTLITH)/SUM(RCV_QTY) *100) PROGRESS FROM
(select RCV_DONO, RCV_ITMCD, RCV_QTY,COALESCE(SUM(ITH_QTY),0) TTLITH from 
	(select RCV_DONO,RCV_ITMCD,SUM(RCV_QTY) RCV_QTY,RCV_WH FROM RCV_TBL
		GROUP BY RCV_DONO,RCV_ITMCD,RCV_WH) a 
	LEFT JOIN ITH_TBL b ON a.RCV_DONO=b.ITH_DOC and a.RCV_ITMCD=b.ITH_ITMCD
WHERE RCV_DONO=@dono AND ITH_FORM='INC-DO'
GROUP BY RCV_DONO,RCV_ITMCD, RCV_QTY) v1
GROUP BY RCV_DONO

GO
/****** Object:  StoredProcedure [dbo].[xsp_progress_scnpnd]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[xsp_progress_scnpnd]
@doc varchar(50)
as 

SELECT PND_DOC, (SUM(TTLSCN)/SUM(PND_QTY) *100) PROGRESS FROM
(select PND_DOC, PND_ITMCD, PND_QTY,COALESCE(SUM(PNDSCN_QTY),0) TTLSCN from 
	(select PND_DOC,PND_ITMCD,SUM(PND_QTY) PND_QTY FROM PND_TBL
		GROUP BY PND_DOC,PND_ITMCD) a 
	LEFT JOIN PNDSCN_TBL b ON a.PND_DOC=b.PNDSCN_DOC and a.PND_ITMCD=b.PNDSCN_ITMCD
WHERE PND_DOC=@doc
GROUP BY PND_DOC,PND_ITMCD, PND_QTY) v1
GROUP BY PND_DOC


GO
/****** Object:  StoredProcedure [dbo].[xsp_progress_scnpndsave]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO


CREATE PROCEDURE [dbo].[xsp_progress_scnpndsave]
@doc varchar(50)
as 
SELECT PND_DOC, (SUM(TTLITH)/SUM(PND_QTY) *100) PROGRESS FROM
(select PND_DOC, PND_ITMCD, PND_QTY,COALESCE(SUM(ITH_QTY),0) TTLITH from 
	(select PND_DOC,PND_ITMCD,SUM(PND_QTY) PND_QTY FROM PND_TBL
		GROUP BY PND_DOC,PND_ITMCD) a 
	LEFT JOIN ITH_TBL b ON a.PND_DOC=b.ITH_DOC and a.PND_ITMCD=b.ITH_ITMCD and b.ITH_FORM='INC-PEN-RM'
WHERE PND_DOC=@doc
GROUP BY PND_DOC,PND_ITMCD, PND_QTY) v1
GROUP BY PND_DOC


GO
/****** Object:  StoredProcedure [dbo].[xsp_progress_scnscr]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[xsp_progress_scnscr]
@doc varchar(50)
as 

SELECT SCR_DOC, (SUM(TTLSCN)/SUM(SCR_QTY) *100) PROGRESS FROM
(select SCR_DOC, SCR_ITMCD, SCR_QTY,COALESCE(SUM(SCRSCN_QTY),0) TTLSCN from 
	(select SCR_DOC,SCR_ITMCD,SUM(SCR_QTY) SCR_QTY FROM SCR_TBL
		GROUP BY SCR_DOC,SCR_ITMCD) a 
	LEFT JOIN SCRSCN_TBL b ON a.SCR_DOC=b.SCRSCN_DOC and a.SCR_ITMCD=b.SCRSCN_ITMCD
WHERE SCR_DOC=@doc
GROUP BY SCR_DOC,SCR_ITMCD, SCR_QTY) v1
GROUP BY SCR_DOC



GO
/****** Object:  StoredProcedure [dbo].[xsp_progress_scnscrsave]    Script Date: 2025-04-28 7:54:03 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO



CREATE PROCEDURE [dbo].[xsp_progress_scnscrsave]
@doc varchar(50)
as 
SELECT SCR_DOC, (SUM(TTLITH)/SUM(SCR_QTY) *100) PROGRESS FROM
(select SCR_DOC, SCR_ITMCD, SCR_QTY,COALESCE(SUM(ITH_QTY),0) TTLITH from 
	(select SCR_DOC,SCR_ITMCD,SUM(SCR_QTY) SCR_QTY FROM SCR_TBL
		GROUP BY SCR_DOC,SCR_ITMCD) a 
	LEFT JOIN ITH_TBL b ON a.SCR_DOC=b.ITH_DOC and a.SCR_ITMCD=b.ITH_ITMCD and b.ITH_FORM='INC-PEN-RM'
WHERE SCR_DOC=@doc
GROUP BY SCR_DOC,SCR_ITMCD, SCR_QTY) v1
GROUP BY SCR_DOC



GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'nomor pendaftaran dari beacukai' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'DLV_TBL', @level2type=N'COLUMN',@level2name=N'DLV_RPDATE'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'MITMSA_TBL', @level2type=N'COLUMN',@level2name=N'MITMSA_RELATION_TYPE'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'MITMSA_TBL', @level2type=N'COLUMN',@level2name=N'MITMSA_LOC_AT_PCB'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'WMS_Inv', @level2type=N'COLUMN',@level2name=N'updated_at'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'WMS_Inv', @level2type=N'COLUMN',@level2name=N'updated_by'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane1', @value=N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[40] 4[20] 2[20] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = 0
         Left = 0
      End
      Begin Tables = 
         Begin Table = "XPSN_VIEW"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 136
               Right = 209
            End
            DisplayFlags = 280
            TopColumn = 9
         End
         Begin Table = "SPL_TBL"
            Begin Extent = 
               Top = 0
               Left = 320
               Bottom = 130
               Right = 490
            End
            DisplayFlags = 280
            TopColumn = 0
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 11
         Column = 1440
         Alias = 900
         Table = 1170
         Output = 1035
         Append = 1400
         NewValue = 1170
         SortType = 1350
         SortOrder = 1410
         GroupBy = 1350
         Filter = 1350
         Or = 1350
         Or = 1350
         Or = 1350
      End
   End
End
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'SPL_VIEW'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=1 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'SPL_VIEW'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane1', @value=N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[40] 4[20] 2[20] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = 0
         Left = 0
      End
      Begin Tables = 
         Begin Table = "a"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 136
               Right = 208
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "b"
            Begin Extent = 
               Top = 138
               Left = 38
               Bottom = 268
               Right = 268
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "c"
            Begin Extent = 
               Top = 6
               Left = 246
               Bottom = 136
               Right = 430
            End
            DisplayFlags = 280
            TopColumn = 0
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 11
         Column = 1440
         Alias = 900
         Table = 1170
         Output = 720
         Append = 1400
         NewValue = 1170
         SortType = 1350
         SortOrder = 1410
         GroupBy = 1350
         Filter = 1350
         Or = 1350
         Or = 1350
         Or = 1350
      End
   End
End
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_infoscntoday_qa'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=1 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_infoscntoday_qa'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane1', @value=N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[40] 4[20] 2[20] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = 0
         Left = 0
      End
      Begin Tables = 
         Begin Table = "a"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 136
               Right = 208
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "b"
            Begin Extent = 
               Top = 138
               Left = 38
               Bottom = 268
               Right = 268
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "c"
            Begin Extent = 
               Top = 6
               Left = 246
               Bottom = 136
               Right = 430
            End
            DisplayFlags = 280
            TopColumn = 0
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 11
         Column = 1440
         Alias = 900
         Table = 1170
         Output = 720
         Append = 1400
         NewValue = 1170
         SortType = 1350
         SortOrder = 1410
         GroupBy = 1350
         Filter = 1350
         Or = 1350
         Or = 1350
         Or = 1350
      End
   End
End
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_infoscntoday_wh'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=1 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_infoscntoday_wh'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane1', @value=N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[40] 4[20] 2[20] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = 0
         Left = 0
      End
      Begin Tables = 
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 11
         Column = 1440
         Alias = 900
         Table = 1170
         Output = 720
         Append = 1400
         NewValue = 1170
         SortType = 1350
         SortOrder = 1410
         GroupBy = 1350
         Filter = 1350
         Or = 1350
         Or = 1350
         Or = 1350
      End
   End
End
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_lastidlgusrplus'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=1 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_lastidlgusrplus'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane1', @value=N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[40] 4[20] 2[20] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = 0
         Left = 0
      End
      Begin Tables = 
         Begin Table = "RCVSCN_TBL"
            Begin Extent = 
               Top = 4
               Left = 350
               Bottom = 167
               Right = 554
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "RCV_TBL"
            Begin Extent = 
               Top = 13
               Left = 69
               Bottom = 176
               Right = 263
            End
            DisplayFlags = 280
            TopColumn = 6
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
      Begin ColumnWidths = 9
         Width = 284
         Width = 1200
         Width = 1200
         Width = 1200
         Width = 1200
         Width = 1200
         Width = 1200
         Width = 1200
         Width = 1200
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 11
         Column = 1440
         Alias = 900
         Table = 1176
         Output = 720
         Append = 1400
         NewValue = 1170
         SortType = 1356
         SortOrder = 1416
         GroupBy = 1350
         Filter = 1356
         Or = 1350
         Or = 1350
         Or = 1350
      End
   End
End
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_rcvbc'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=1 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'v_rcvbc'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane1', @value=N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[40] 4[20] 2[20] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = 0
         Left = 0
      End
      Begin Tables = 
         Begin Table = "RCV_TBL"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 136
               Right = 224
            End
            DisplayFlags = 280
            TopColumn = 0
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 12
         Column = 1440
         Alias = 900
         Table = 1170
         Output = 720
         Append = 1400
         NewValue = 1170
         SortType = 1350
         SortOrder = 1410
         GroupBy = 1350
         Filter = 1350
         Or = 1350
         Or = 1350
         Or = 1350
      End
   End
End
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'vrcv_tblg'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=1 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'vrcv_tblg'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane1', @value=N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[40] 4[20] 2[20] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = 0
         Left = 0
      End
      Begin Tables = 
         Begin Table = "a"
            Begin Extent = 
               Top = 6
               Left = 454
               Bottom = 136
               Right = 669
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "b"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 136
               Right = 235
            End
            DisplayFlags = 280
            TopColumn = 0
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 11
         Column = 1440
         Alias = 900
         Table = 1170
         Output = 720
         Append = 1400
         NewValue = 1170
         SortType = 1350
         SortOrder = 1410
         GroupBy = 1350
         Filter = 1350
         Or = 1350
         Or = 1350
         Or = 1350
      End
   End
End
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'XPGRN_VIEW'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=1 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'XPGRN_VIEW'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPane1', @value=N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[40] 4[20] 2[20] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = 0
         Left = 0
      End
      Begin Tables = 
         Begin Table = "PPSN1_TBL_1"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 199
               Right = 226
            End
            DisplayFlags = 280
            TopColumn = 14
         End
         Begin Table = "PPSN2_TBL_1"
            Begin Extent = 
               Top = 0
               Left = 360
               Bottom = 198
               Right = 547
            End
            DisplayFlags = 280
            TopColumn = 4
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
      Begin ColumnWidths = 9
         Width = 284
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 11
         Column = 1620
         Alias = 900
         Table = 1950
         Output = 720
         Append = 1400
         NewValue = 1170
         SortType = 1350
         SortOrder = 1410
         GroupBy = 1350
         Filter = 1350
         Or = 1350
         Or = 1350
         Or = 1350
      End
   End
End
' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'XPSN_VIEW'
GO
EXEC sys.sp_addextendedproperty @name=N'MS_DiagramPaneCount', @value=1 , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'VIEW',@level1name=N'XPSN_VIEW'
GO
USE [master]
GO
ALTER DATABASE [PSI_WMS] SET  READ_WRITE 
GO
