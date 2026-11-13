/*
 * English word breaks for the words used on this website (checked by hand, not guessed by rules).
 * typeset.js puts an invisible "soft hyphen" at these points, so a long word can be split across two lines
 * and justified text gets even spacing. A word that is not listed is simply never split.
 *
 * To add a word: write it in lower case with hyphens at the syllable breaks, e.g. "ac-a-dem-ic".
 * Breaks that would leave fewer than 3 letters on either side are ignored automatically.
 * Names of people and places are left out on purpose.
 */
const WORDS = `
aca-dem-ic aca-dem-ics ac-ces-si-bil-i-ty ac-com-mo-da-tion ac-com-plished ac-count-abil-i-ty ac-coun-tant ac-count-ing ac-cred-it-ed
achieve-ments achiev-ing acous-tics ac-tive-ly ac-tiv-i-ties ad-di-tion ad-di-tion-al ad-di-tion-al-ly ad-min-is-tra-tion ad-min-is-tra-tor
ad-mis-sions ad-vanced ad-vance-ment ad-vanc-ing ad-vi-so-ry af-fil-i-at-ed af-fil-i-a-tion af-ford-able al-ge-bra al-go-rithms
al-low-ance al-low-ing along-side al-ter-na-tive-ly am-bi-tions amer-i-can anal-y-sis an-a-lyst an-a-lyt-ics an-droid an-ni-ver-sary
an-oth-er any-where ap-pli-cants ap-pli-ca-tion ap-pli-ca-tions ap-pre-ci-a-tion ap-proach-able ap-proved ar-chi-tec-ture
ar-range-ments ar-ti-fi-cial art-ist-ry as-pir-ing as-sess-ment as-sis-tance as-sis-tant as-so-ci-a-tion at-mos-phere at-ten-dees
at-ten-tion au-di-tor aus-tra-lia au-then-tic-i-ty au-thor-i-ty avail-able await-ing award-ing awe-some bach-e-lor bach-e-lors
back-end bal-ance bar-ri-ers becom-ing behav-ior behav-iour ben-e-fi-cial bind-ing block-chain block-chains bring-ing build-ing
bur-sar-ies busi-ness cal-cu-lus cal-i-bre can-di-dates can-teen cap-stone cat-e-go-ries cel-e-brat-ed cel-e-brat-ing cel-e-bra-tion
cel-e-bra-tions cen-tered cen-tral cen-tres cen-tu-ry cer-e-mo-nies cer-e-mo-ny cer-tif-i-cate cer-tif-i-cates chair-man chal-lenges
chan-cel-lor chang-ing child-hood cit-i-zens class-room class-rooms clean-ing col-lab-o-rate col-lab-o-ra-tion col-lab-o-ra-tions
col-lab-o-ra-tive col-lab-o-ra-tive-ly col-lec-tion col-lege col-leges com-bi-na-tion com-bine com-fort com-fort-able com-merce
com-mis-sion-er com-mit-ment com-mit-ted com-mon-wealth com-mu-ni-ca-tion com-mu-ni-ca-tive com-mu-ni-ty com-pa-nies com-pen-sa-tion
com-pe-ten-cies com-pe-ti-tions com-plete com-plet-ed com-ple-tion com-plex-i-ty com-pris-ing com-put-er com-put-ing con-cepts con-cern
con-ces-sions con-clud-ed con-du-cive con-duct con-duct-ed con-fi-dence con-fi-dent con-firm con-nect con-nec-tions con-nec-tiv-i-ty
con-nects con-sid-er-ing con-sul-tant con-sult-ing con-tact con-tent con-tin-ue con-tin-ued con-tin-ues con-trib-ute con-tri-bu-tion
con-trol con-ve-nient con-ver-sa-tions con-vo-ca-tion co-op-er-at-ing co-op-er-a-tion co-or-di-nat-ing co-or-di-na-tor cor-po-rate
coun-cil coun-sel-ling coun-tries coun-try cov-er-age cov-ered creat-ed creat-ing cre-a-tive cre-a-tiv-i-ty cryp-tog-ra-phy cul-ture
cur-ric-u-la cur-ric-u-lum cus-tom-ized cy-ber-se-cu-ri-ty cy-ber-space data-base data-bases de-cem-ber decid-ing ded-i-cat-ed
ded-i-ca-tion defin-ing del-e-gates deliv-er-ing dem-o-crat-ic dem-on-strate dem-on-strat-ed dem-on-stra-tions depart-ment
deploy-ment design-er desir-able des-ti-na-tion detec-tion devel-op devel-oped devel-op-er devel-op-ment dif-fers dig-i-tal
dig-ni-tar-ies direc-tion direc-tor dis-as-ter dis-ci-pline dis-course dis-cuss dis-cus-sion dis-cus-sions dis-tin-guished dis-trict
divi-sion-al east-ern eco-nom-i-cal-ly eco-nom-ics econ-o-my ed-u-ca-tion ed-u-ca-tion-al ef-fec-tive-ly ef-fi-cien-cy ef-fi-cient
elec-tion elec-tive elec-tives elec-tor-al elec-tri-cal elec-tron-ics el-i-gi-ble em-brac-ing emerg-ing emo-tion-al-ly em-pha-siz-ing
em-ploy-abil-i-ty em-ploy-ee em-ploy-ment em-pow-er em-pow-er-ing en-cour-age en-cour-aged en-cour-ag-ing en-deav-ors en-gage-ment
en-gi-neer-ing en-rich-ing en-roll-ments en-sur-ing en-ter-prise en-thu-si-as-tic en-tre-pre-neurs en-tre-pre-neur-ship en-trust-ed
en-vi-ron-ment en-vi-ron-men-tal equiv-a-lent es-sen-tial es-sen-tials es-tab-lished es-tab-lish-ing es-teemed eth-i-cal euro-pe-an
eval-u-a-tion every-one ex-am-i-na-tion ex-am-ple ex-cel-lence ex-cel-len-cy ex-cel-lent ex-change ex-clu-sive ex-cur-sions
ex-ec-u-tive ex-pan-sion ex-penses ex-pe-ri-ence ex-pe-ri-enced ex-pe-ri-ences ex-per-i-ment ex-per-tise ex-perts ex-plained
ex-plo-ra-tion ex-plor-a-to-ry ex-plore ex-plor-ing ex-po-sure ex-pressed ex-tends fa-cil-i-ties fac-ul-ties fac-ul-ty feb-ru-ary
fic-tion fi-nal-ist fi-nance fi-nan-cial fi-nan-cial-ly fin-land fin-nish for-eign fo-ren-sics fos-ter-ing foun-da-tion
foun-da-tion-al foun-da-tions found-er front-end fruit-ful ful-fill func-tion-al fun-da-men-tals fund-ing fur-ni-ture fur-ther
gain-ing gen-er-a-tions gen-er-ous glob-al-ly gov-er-nance gov-ern-ing gov-ern-ment grad-u-at-ed grad-u-ates grad-u-a-tion
gram-mar grant-ed graph-ic grat-i-tude groom-ing grow-ing guid-ance hack-ing han-dling hard-ware heart-felt help-desk help-ing
hi-er-ar-chy high-light high-light-ed his-tor-ic ho-lis-tic hon-ored hon-or-ing hon-oured hos-pi-tal-i-ty host-ing hun-dreds im-mense
im-por-tance im-por-tant in-clud-ing in-cor-po-rat-ed in-creas-ing in-di-vid-u-al in-di-vid-u-als in-dus-tri-al in-dus-tries
in-dus-try in-for-ma-tion in-for-ma-tive ini-tia-tive ini-tia-tives in-no-va-tion in-no-va-tive in-sights in-spire in-spir-ing
in-sti-tute in-sti-tutes in-sti-tu-tion in-sti-tu-tion-al in-sti-tu-tions in-sur-ance in-takes in-te-gra-tion in-teg-ri-ty
in-tel-lec-tu-al in-tel-lec-tu-al-ly in-tel-li-gence in-tel-li-gent in-ter-act in-ter-ac-tion in-ter-ac-tions in-ter-est-ed
in-ter-ests in-ter-fac-es in-ter-na-tion-al in-ter-na-tion-al-ly in-ter-net in-tern-ship in-tro-duce in-tro-duc-tion
in-tro-duc-to-ry in-tru-sion in-vest-ment is-land-wide jap-a-nese joint-ly jour-nals jour-ney knowl-edge knowl-edge-able lan-guage
lan-guages lead-ers lead-er-ship lead-ing learn-ers learn-ing lec-ture lec-tur-er lec-tur-ers leg-a-cies leg-end-ary life-cy-cles
lin-guis-tics lis-ten-ing lit-er-a-ture look-ing main-tain main-tained malay-sia man-age-ment man-ag-er man-age-ri-al mar-ket-ing
mas-ters ma-te-ri-als math-e-mat-ics mat-plot-lib mean-ing-ful me-chan-ics med-i-cine meet-ing mem-bers mem-o-ra-ble mem-o-ran-dum
meth-od-ol-o-gy meth-ods mi-cro-eco-nom-ics mi-cro-soft mile-stone mile-stones min-i-mum min-is-try min-utes mis-sion mod-er-a-tion
mod-ules mold-ing mor-phol-o-gy mo-ti-va-tion mo-ti-va-tion-al mul-ti-me-dia na-tion-al na-tion-al-ly nat-u-ral nav-i-gat-ing
nec-es-sary net-work net-work-ing net-works no-vem-ber nur-tur-ing ob-ser-va-tion oc-ca-sion oc-to-ber of-fer-ing of-fi-cer of-fi-cial
open-ing op-er-at-ed op-er-ates op-er-at-ing op-er-a-tion op-er-a-tions op-por-tu-ni-ties op-por-tu-ni-ty op-ti-mal op-ti-mi-za-tion
op-tions or-di-nary or-ga-ni-za-tion or-ga-ni-za-tion-al or-ga-nized ori-en-ta-tion ori-ent-ed out-come out-stand-ing over-all
over-come over-see over-sees pa-cif-ic pack-age pa-per-work par-lour par-tic-i-pants par-tic-i-pat-ed par-tic-i-pa-tion part-ner
part-ner-ship part-ner-ships pas-sion pas-sion-ate path-ways per-cent-age per-for-mance per-son-al pho-net-ics pho-nol-o-gy
phys-i-cal-ly phys-ics phys-i-ol-o-gy piv-ot-al place-ment plan-ning plat-form plat-forms pos-i-tive pos-i-tive-ly pos-si-bil-i-ties
pos-si-bly post-grad-u-ate po-ten-tial prac-ti-cal prac-tice prac-tices prac-ti-tion-er prag-mat-ics prepar-ing pre-pro-cess-ing
pres-ence pres-ent preserv-ing pres-ti-gious preven-tion pri-ma-ry prin-ci-ples pri-va-cy prob-a-bil-i-ty pro-ceed pro-cess
pro-cess-ing pro-duc-tive pro-fes-sion-al pro-fes-sion-al-ism pro-fes-sion-als pro-fes-sions pro-gram pro-gramme pro-grammes
pro-gram-ming pro-grams prog-ress proj-ect proj-ects pro-mote pro-nun-ci-a-tion pro-vide pro-vid-ed pro-vid-er pro-vides pro-vid-ing
prov-ince psy-cho-lin-guis-tics psy-chol-o-gy pur-pos-es qual-i-fi-ca-tion qual-i-fied qual-i-ty ques-tions rap-id-ly ra-tio-nal-i-ty
read-ing re-af-firmed receiv-ing rec-og-nised rec-ords recruit-ment reflect-ed reflec-tion reg-is-trar reg-is-tra-tion
reg-is-tra-tions reg-u-lar reg-u-lar-ly reg-u-lat-ed rela-tions rela-tion-ships rel-e-vance rel-e-vant reli-a-bil-i-ty reli-able
remark-able remind-ing report-ing rep-re-sen-ta-tives repub-lic rep-u-ta-tion require-ments resound-ing respon-si-bil-i-ties
respon-si-bil-i-ty respon-si-ble reward-ing ro-bot-ics safe-guard-ing sat-el-lite schol-ar-ship schol-ar-ships sci-ence sci-ences
seam-less seat-ing sec-re-tar-ies sec-tion sec-tors secu-ri-ty select-ed selec-tion seman-tics semes-ter sep-tem-ber ser-vice ser-vices
serv-ing ses-sion ses-sions sev-er-al shap-ing show-cas-ing show-ing sig-nif-i-cant sign-ing social-ist soci-ety socio-lin-guis-tics
soft-ware speak-er speak-ing special-ized spon-sored spon-sor-ship stan-dard stan-dards stand-ing state-ment sta-tis-ti-cal
sta-tis-tics stay-ing steg-a-nog-ra-phy stra-te-gic strength-en strength-ens struc-ture struc-tured struc-tures stu-dent stu-dents
stud-ies study-ing sty-lis-tics sub-ject sub-jects sub-sid-iary suc-cess suc-cess-ful suc-cess-ful-ly su-per-vised sup-port
sup-port-ed sup-port-ers sup-port-ing sup-port-ive sur-gery sus-tain-abil-i-ty sym-bol-ized sys-tems tal-ent-ed tal-ents teach-ers
teach-ing team-work tech-link tech-ni-cal tech-niques tech-nol-o-gies tech-nol-o-gy test-ing there-by thought-ful-ly through-out
tol-er-ance tour-ism tour-na-ments tra-di-tion-al traf-fic train-ees train-ing trans-ac-tion trans-for-ma-tion trans-for-ma-tive
trans-la-tion trans-par-en-cy trib-ute tu-i-tion ty-pog-ra-phy un-der-grad-u-ate un-der-stand-ing un-fet-tered un-in-ter-rupt-ed
uni-ver-si-ties uni-ver-si-ty un-su-per-vised un-wa-ver-ing up-dates up-lift-ing uti-lized val-u-able val-u-ing vari-eties vari-ety
var-i-ous vir-tu-al vir-tu-al-i-za-tion vi-su-al-i-za-tion vo-cab-u-lary vo-ca-tion-al waiv-ers web-page web-site wel-come wel-com-ing
what-ev-er win-ners with-out won-der-ful won-drous work-ing work-shop work-shops wran-gling writ-ing your-self
`;

const BREAKS = new Map();
for (const token of WORDS.split(/\s+/)) {
    if (!token.includes('-')) continue;
    const cuts = [];
    let at = 0;
    token.split('-').slice(0, -1).forEach((part) => { at += part.length; cuts.push(at); });
    BREAKS.set(token.replace(/-/g, ''), cuts);
}

/** Puts invisible soft hyphens into the listed words of a piece of text. Words not listed are left alone. */
export function softHyphenate(text) {
    return text.replace(/[A-Za-z]{7,}/g, (word) => {
        const cuts = BREAKS.get(word.toLowerCase());
        if (!cuts) return word;
        let out = '';
        let last = 0;
        for (const c of cuts) {
            if (c < 3 || word.length - c < 3) continue; // keep 3+ letters on each side of a break
            out += word.slice(last, c) + '­';
            last = c;
        }
        return out + word.slice(last);
    });
}
